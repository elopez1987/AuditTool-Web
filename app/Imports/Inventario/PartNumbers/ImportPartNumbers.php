<?php

declare(strict_types=1);

namespace App\Imports\Inventario\PartNumbers;

use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

class ImportPartNumbers implements ToCollection, WithChunkReading, WithHeadingRow
{
    /** Límite de una columna entera de 32 bits con signo (SQL Server/MySQL INT). */
    private const MAX_INT = 2147483647;

    private const OPTIONAL_COLUMNS = [
        'abccod' => 'abccod',
        'shelflife' => 'shelflife',
        'barcode' => 'barcode',
        'location' => 'auxiliary01',
        'codeean' => 'codeean',
        'codedun' => 'codedun',
    ];

    private readonly string $centerid;

    private readonly string $warehouseid;

    private readonly ?int $defaultCategory01Id;

    private readonly ?int $defaultCategory02Id;

    /** Si la columna prtnum de esta base de datos es numérica (int/bigint/...). */
    private readonly bool $prtnumIsInteger;

    /** Filas que no se pudieron procesar en el chunk actual. */
    protected array $failedRows = [];

    /** Total de filas omitidas en todo el import (para informar al usuario). */
    protected int $totalFailedCount = 0;

    public function __construct()
    {
        $access = DB::table('securityaccesswarehouses')
            ->where('username', Auth::user()->username)
            ->first(['centerid', 'warehouseid']);

        $this->centerid = $access->centerid ?? '';
        $this->warehouseid = $access->warehouseid ?? '';

        $this->defaultCategory01Id = InventoryPartNumberCategory01::controlAccessUser()->value('id');
        $this->defaultCategory02Id = InventoryPartNumberCategory02::controlAccessUser()->value('id');

        $this->prtnumIsInteger = $this->isIntegerColumn('inventorypartnumbers', 'prtnum');
    }

    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $presentOptionalColumns = $this->presentOptionalColumns($rows->first());

        // Indexado por prtnum: si el archivo trae el mismo prtnum repetido
        // dentro del mismo chunk, nos quedamos con la última ocurrencia en
        // vez de mandar dos filas con la misma llave (centerid, warehouseid,
        // prtnum) al mismo UPSERT masivo. SQL Server rechaza un MERGE cuyo
        // origen matchea la misma fila destino más de una vez con
        // "attempted to UPDATE or DELETE the same row more than once".
        $upsertData = [];

        foreach ($rows as $row) {
            $prepared = $this->prepareRow($row, $presentOptionalColumns);

            if ($prepared === null) {
                continue;
            }

            if (isset($upsertData[$prepared['prtnum']])) {
                $this->failedRows[] = "prtnum {$prepared['prtnum']} está repetido en el archivo (mismo lote); se importó solo la última ocurrencia.";
            }

            $upsertData[$prepared['prtnum']] = $prepared;
        }

        if (! empty($upsertData)) {
            $this->upsertChunk(array_values($upsertData));
        }

        $this->logFailedRows();
    }

    private function prepareRow(Collection $row, array $presentOptionalColumns): ?array
    {
        $prtnum = $this->toPlainString($row['prtnum'] ?? null);
        if ($prtnum === null) {
            $this->failedRows[] = 'prtnum es obligatorio y llegó vacío.';

            return null;
        }

        if ($this->prtnumIsInteger && ! $this->isValidIntegerPrtnum($prtnum)) {
            $this->failedRows[] = "prtnum '{$prtnum}' no es válido para la columna numérica de esta base de datos (máx. " . self::MAX_INT . ').';

            return null;
        }

        $lngdsc = isset($row['lngdsc']) ? trim((string) $row['lngdsc']) : '';
        if ($lngdsc === '') {
            $this->failedRows[] = "prtnum {$prtnum}: lngdsc es obligatorio.";

            return null;
        }

        $category01id = $this->sanitizeInt($row['category01id'] ?? null) ?? $this->defaultCategory01Id;
        $category02id = $this->sanitizeInt($row['category02id'] ?? null) ?? $this->defaultCategory02Id;

        if ($category01id === null || $category02id === null) {
            $this->failedRows[] = "prtnum {$prtnum}: category01id/category02id inválido y no hay categoría por defecto configurada.";

            return null;
        }

        $data = [
            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'prtnum' => $prtnum, // Obligatorio
            'lngdsc' => $lngdsc, // Obligatorio
            'category01id' => $category01id, // Obligatorio
            'category02id' => $category02id, // Obligatorio
            'untcas' => $this->sanitizeInt($row['untcas'] ?? null) ?? 1, // Obligatorio
            'untpal' => $this->sanitizeInt($row['untpal'] ?? null) ?? 1, // Obligatorio
            'statusid' => 'E',
            'schemaid' => 'STS',
        ];

        foreach ($presentOptionalColumns as $fileColumn => $dbColumn) {
            $value = $row[$fileColumn] ?? null;

            $data[$dbColumn] = match ($dbColumn) {
                'shelflife' => $this->sanitizeInt($value) ?? 0,
                'barcode', 'codeean', 'codedun' => $this->toPlainString($value) ?? '0',
                default => $value !== null ? trim((string) $value) : '0',
            };
        }

        return $data;
    }

    private function upsertChunk(array $upsertData): void
    {
        $updateColumns = array_values(array_diff(
            array_keys($upsertData[0]),
            ['centerid', 'warehouseid', 'prtnum'],
        ));

        try {
            // Sin DB::transaction() propio: maatwebsite/excel ya ejecuta
            // collection() dentro de su propia transacción por chunk
            // (config('excel.transactions.handler') = 'db'). Anidar otra
            // transacción aquí obligaba a Laravel a usar un SAVEPOINT
            // (SAVE TRANSACTION transN); en SQL Server, un error dentro de
            // ese savepoint puede invalidarlo sin invalidar la transacción
            // externa, y el ROLLBACK TRANSACTION transN termina fallando
            // con "No transaction or savepoint of that name was found",
            // dejando el contador de transacciones de Laravel
            // desincronizado para los siguientes chunks. El upsert es una
            // sola sentencia atómica: si falla, no se aplica nada del lote,
            // así que la atomicidad por chunk se conserva de todas formas.
            DB::table('inventorypartnumbers')->upsert(
                $upsertData,
                ['centerid', 'warehouseid', 'prtnum'],
                $updateColumns,
            );
        } catch (Throwable $e) {
            Log::error('Error al guardar un lote de números de parte', [
                'centerid' => $this->centerid,
                'warehouseid' => $this->warehouseid,
                'total_filas' => count($upsertData),
                'error' => $e->getMessage(),
            ]);
            $this->failedRows[] = 'No se pudo guardar un lote de ' . count($upsertData) . ' fila(s): ' . $e->getMessage();
        }
    }

    private function presentOptionalColumns(Collection $firstRow): array
    {
        $rowKeys = $firstRow->keys();

        return collect(self::OPTIONAL_COLUMNS)
            ->filter(fn ($dbColumn, $fileColumn) => $rowKeys->contains($fileColumn))
            ->all();
    }

    private function toPlainString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = is_float($value) ? sprintf('%.0f', $value) : trim((string) $value);
        $string = trim($string);

        return $string === '' ? null : $string;
    }

    private function sanitizeInt(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        if ($value === '' || ! is_numeric($value)) {
            return null;
        }

        $intValue = (int) round((float) $value);

        return $this->fitsInInt((string) $intValue) ? $intValue : null;
    }

    private function fitsInInt(string $numericValue): bool
    {
        if (! ctype_digit(ltrim($numericValue, '-'))) {
            return true;
        }

        return abs((float) $numericValue) <= self::MAX_INT;
    }

    private function isValidIntegerPrtnum(string $prtnum): bool
    {
        $normalized = ltrim($prtnum, '-');

        if ($normalized === '' || ! ctype_digit($normalized)) {
            return false;
        }

        return abs((float) $prtnum) <= self::MAX_INT;
    }

    private function isIntegerColumn(string $table, string $column): bool
    {
        try {
            $type = Schema::getColumnType($table, $column);
        } catch (Throwable $e) {
            return false;
        }

        return in_array(strtolower($type), ['integer', 'int', 'bigint', 'smallint', 'tinyint'], true);
    }

    private function logFailedRows(): void
    {
        if (empty($this->failedRows)) {
            return;
        }

        $this->totalFailedCount += count($this->failedRows);

        Log::warning('Filas omitidas al importar números de parte', [
            'centerid' => $this->centerid,
            'warehouseid' => $this->warehouseid,
            'total_omitidas' => count($this->failedRows),
            'detalle' => $this->failedRows,
        ]);
        $this->failedRows = [];
    }

    public function getFailedRowsCount(): int
    {
        return $this->totalFailedCount;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
