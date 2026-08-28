<?php

declare(strict_types=1);

namespace App\Imports\Inventario\PartNumbers;

use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

/**
 * El archivo de SAP puede traer basura en la columna prtnum (p.ej. texto
 * como "Auditor" por una fila corrida o mal alineada). Antes ese valor se
 * enviaba tal cual a un whereIn('prtnum', ...) contra inventorypartnumbers;
 * en bases de datos donde esa columna es INT (como en este cliente), SQL
 * Server truena al intentar convertir el nvarchar 'Auditor' a int, y como
 * el import corre dentro de una transacción por chunk, se pierde el chunk
 * completo.
 *
 * Ahora cada prtnum se normaliza a texto plano (evitando la notación
 * científica que PhpSpreadsheet puede generar para celdas numéricas) y se
 * valida ANTES de tocar la base de datos: si la columna prtnum de esta base
 * de datos es numérica, cualquier valor que no sea un entero válido se
 * descarta como fila inválida en vez de llegar al whereIn().
 */
class ImportInventoryStockSap implements ShouldQueue, ToCollection, WithChunkReading, WithHeadingRow
{
    /** Límite de una columna entera de 32 bits con signo (SQL Server/MySQL INT). */
    private const MAX_INT = 2147483647;

    /** Filas que no se pudieron procesar, para reportar al final. */
    protected array $failedRows = [];

    /** Si la columna prtnum de esta base de datos es numérica (int/bigint/...). */
    private readonly bool $prtnumIsInteger;

    public function __construct(
        protected readonly InventoryHead $inventoryHead
    ) {
        $this->prtnumIsInteger = $this->isIntegerColumn('inventorypartnumbers', 'prtnum');
    }

    /**
     * Se ejecuta una vez POR CHUNK (no por fila), gracias a ToCollection.
     * El archivo de SAP solo trae prtnum y quantity.
     */
    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $validRows = [];

        foreach ($rows as $row) {
            $prtnum = $this->normalizePrtnum($row['prtnum'] ?? null);
            if ($prtnum === null) {
                $this->failedRows[] = "prtnum inválido o vacío: '" . ($row['prtnum'] ?? '') . "'";

                continue;
            }

            $quantity = $this->sanitizeQuantity($row['quantity'] ?? null);
            if ($quantity === null) {
                $this->failedRows[] = "prtnum {$prtnum}: quantity inválida: '" . ($row['quantity'] ?? '') . "'";

                continue;
            }

            $validRows[] = ['prtnum' => $prtnum, 'quantity' => $quantity];
        }

        if (empty($validRows)) {
            $this->logFailedRows();

            return;
        }

        $partNumbers = collect($validRows)->pluck('prtnum')->unique()->values();

        $partNumberRecords = InventoryPartnumber::where('centerid', $this->inventoryHead->centerid)
            ->where('warehouseid', $this->inventoryHead->warehouseid)
            ->whereIn('prtnum', $partNumbers)
            ->pluck('id', 'prtnum');

        // --- Armar el array de upsert en memoria (sin tocar la BD todavía) ---
        $upsertData = [];

        foreach ($validRows as $row) {
            $partNumberId = $partNumberRecords->get($row['prtnum']);
            if (! $partNumberId) {
                $this->failedRows[] = "Número de parte no existe: {$row['prtnum']}";

                continue;
            }

            $upsertData[] = [
                'inventory_head_id' => $this->inventoryHead->id,
                'inventorypartnumber_id' => $partNumberId,
                'quantity' => $row['quantity'],
            ];
        }

        // --- UN solo upsert masivo (crear o actualizar) para todo el chunk ---
        if (! empty($upsertData)) {
            DB::transaction(function () use ($upsertData) {
                DB::table('inventory_stock_sap')->upsert(
                    $upsertData,
                    ['inventory_head_id', 'inventorypartnumber_id'], // columnas únicas (requiere índice único compuesto)
                    ['quantity']
                );
            });
        }

        $this->logFailedRows();
    }

    /**
     * Convierte prtnum a un string limpio (sin notación científica) y, si
     * la columna real es numérica en esta base de datos, descarta valores
     * que no sean enteros válidos dentro de rango -evitando así que un
     * valor como "Auditor" llegue al whereIn() y tumbe el chunk completo.
     */
    private function normalizePrtnum(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = is_float($value) ? sprintf('%.0f', $value) : trim((string) $value);
        $string = trim($string);

        if ($string === '') {
            return null;
        }

        if ($this->prtnumIsInteger) {
            $normalized = ltrim($string, '-');
            if ($normalized === '' || ! ctype_digit($normalized) || abs((float) $string) > self::MAX_INT) {
                return null;
            }
        }

        return $string;
    }

    private function sanitizeQuantity(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        if ($value === '' || ! is_numeric($value)) {
            return null;
        }

        return (int) round((float) $value);
    }

    /**
     * Cada base de datos de cliente puede tener prtnum como INT o como
     * VARCHAR; se detecta en tiempo de ejecución para no rechazar valores
     * válidos en una columna de texto.
     */
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
        if (! empty($this->failedRows)) {
            Log::warning('Filas omitidas en import de inventario SAP', [
                'inventory_head_id' => $this->inventoryHead->id,
                'total_omitidas' => count($this->failedRows),
                'detalle' => $this->failedRows,
            ]);

            $this->failedRows = [];
        }
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 1000; // sin N+1, un chunk más grande es más eficiente
    }
}
