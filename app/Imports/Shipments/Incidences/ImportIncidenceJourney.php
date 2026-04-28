<?php

namespace App\Imports\Shipments\Incidences;

use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Models\Shipments\StorageLogInverse;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class ImportIncidenceJourney implements ToCollection, WithHeadingRow
{
    /**
     * Estatus que se asigna a la incidencia recién importada.
     */
    private const STATUS_INCIDENCE = 'CD';

    /**
     * Etiqueta usada al inicio de cada mensaje de log para facilitar
     * el filtrado en el archivo de logs.
     */
    private const LOG_TAG = '[ImportIncidenceJourney]';

    /**
     * Cache local de razon codes ya consultados durante esta importación,
     * para evitar repetir el mismo SELECT por cada fila.
     *
     * @var array<string, StorageCategoryIncidenceDetail|null>
     */
    private array $razoncodeCache = [];

    public function collection(Collection $rows): void
    {
        $total = $rows->count();
        $processed = 0;
        $skipped = 0;
        $errors = 0;

        Log::info(self::LOG_TAG . ' Inicio de importación', [
            'total_rows' => $total,
            'user' => optional(\Auth::user())->username,
        ]);

        foreach ($rows as $index => $row) {
            // +2 = encabezado (fila 1 del Excel) + base 1 para humanos
            $rowNumber = (int) $index + 2;
            $shipmentid = isset($row['shipmentid']) ? trim((string) $row['shipmentid']) : '';

            try {
                Log::debug(self::LOG_TAG . " Procesando fila #{$rowNumber}", [
                    'shipmentid' => $shipmentid,
                ]);

                if ($shipmentid === '') {
                    $this->logSkip($rowNumber, $shipmentid, 'Shipmentid vacío');
                    StorageLogInverse::createNewLog('Fila sin número de embarque', null);
                    $skipped++;

                    continue;
                }

                $orderDelivery = OrderDeliverie::controlAccessUser()
                    ->where('shipmentid', $shipmentid)
                    ->first();

                if (empty($orderDelivery)) {
                    $this->logSkip($rowNumber, $shipmentid, 'Embarque no encontrado o sin acceso');
                    StorageLogInverse::createNewLog('No existe número de embarque ingresado', $shipmentid);
                    $skipped++;

                    continue;
                }

                $razoncode = $this->findRazoncode($row['razoncode'] ?? null);
                if ($razoncode === null) {
                    $this->logSkip($rowNumber, $shipmentid, 'Razon code no encontrado', [
                        'razoncode' => $row['razoncode'] ?? null,
                    ]);
                    StorageLogInverse::createNewLog('No existe el razon code ingresado', $shipmentid);
                    $skipped++;

                    continue;
                }

                $razoncodeDetail = $this->findRazoncode($row['auxiliary02'] ?? null);
                if ($razoncodeDetail === null) {
                    $this->logSkip($rowNumber, $shipmentid, 'Razon code detalle (auxiliary02) no encontrado', [
                        'auxiliary02' => $row['auxiliary02'] ?? null,
                    ]);
                    StorageLogInverse::createNewLog('No existe el razon code detalle (auxiliary02)', $shipmentid);
                    $skipped++;

                    continue;
                }

                $rawDownloadDate = $row['downloaddate'] ?? null;
                $downloadDate = $this->parseDownloadDate($rawDownloadDate);
                if ($downloadDate === null) {
                    $this->logSkip($rowNumber, $shipmentid, 'downloaddate inválido o vacío', [
                        'downloaddate' => $rawDownloadDate,
                    ]);
                    StorageLogInverse::createNewLog(
                        'La columna downloaddate no es una fecha válida',
                        $shipmentid,
                    );
                    $skipped++;

                    continue;
                }

                DB::transaction(function () use ($orderDelivery, $row, $razoncode, $shipmentid, $rowNumber, $downloadDate) {
                    $incidence = StorageIncidenceJourney::updateOrCreate([
                        'centerid' => $orderDelivery->centerid,
                        'warehouseid' => $orderDelivery->warehouseid,
                        'orderid' => $orderDelivery->id,
                        'carrierid' => $orderDelivery->carrierid,
                        'statusid' => self::STATUS_INCIDENCE,
                    ]);

                    Log::debug(self::LOG_TAG . " Incidencia upserted en fila #{$rowNumber}", [
                        'shipmentid' => $shipmentid,
                        'folioid' => $incidence->folioid ?? null,
                        'orderid' => $incidence->orderid,
                        'wasRecentlyCreated' => $incidence->wasRecentlyCreated,
                    ]);

                    StorageIncidenceJourneyDetails::updateOrCreate([
                        'centerid' => $incidence->centerid,
                        'warehouseid' => $incidence->warehouseid,
                        'orderid' => $incidence->orderid,
                        'delivery' => $row['delivery'] ?? null,
                        'partnumberid' => $row['partnumberid'] ?? null,
                    ], [
                        'quantity' => $row['quantity'] ?? null,
                        'unitmeasure' => $row['unitmeasure'] ?? null,
                        'razoncode' => $razoncode->getKey(),
                        'downloaddate' => $downloadDate,
                        'partnumberiderror' => $row['partnumberiderror'] ?? null,
                        'comment' => $row['comment'] ?? null,
                        'auxiliary01' => $row['auxiliary01'] ?? null,
                        'auxiliary02' => $razoncodeDetail->getKey(),
                        'auxiliary03' => $row['auxiliary03'] ?? null,
                        'auxiliary04' => $row['auxiliary04'] ?? null,
                        'auxiliary05' => $row['auxiliary05'] ?? null,
                        'statusiddetail' => $row['statusiddetail'] ?? null,
                    ]);
                });

                $processed++;
                Log::info(self::LOG_TAG . " Fila #{$rowNumber} procesada correctamente", [
                    'shipmentid' => $shipmentid,
                ]);
            } catch (Throwable $e) {
                $errors++;
                Log::error(self::LOG_TAG . " Error procesando fila #{$rowNumber}", [
                    'shipmentid' => $shipmentid,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                StorageLogInverse::createNewLog(
                    'Error al importar incidencia: ' . $e->getMessage(),
                    $shipmentid !== '' ? $shipmentid : null,
                );
            }
        }

        Log::info(self::LOG_TAG . ' Fin de importación', [
            'total_rows' => $total,
            'processed' => $processed,
            'skipped' => $skipped,
            'errors' => $errors,
        ]);
    }

    /**
     * Busca un StorageCategoryIncidenceDetail por su columna auxiliary05,
     * cacheando el resultado para reutilizarlo en filas subsecuentes.
     */
    private function findRazoncode(mixed $auxiliary05): ?StorageCategoryIncidenceDetail
    {
        $key = trim((string) ($auxiliary05 ?? ''));
        if ($key === '') {
            return null;
        }

        if (! array_key_exists($key, $this->razoncodeCache)) {
            $this->razoncodeCache[$key] = StorageCategoryIncidenceDetail::where('auxiliary05', $key)->first();
        }

        return $this->razoncodeCache[$key];
    }

    /**
     * Helper centralizado para registrar filas omitidas con un formato consistente.
     *
     * @param  array<string, mixed>  $extra
     */
    private function logSkip(int $rowNumber, string $shipmentid, string $reason, array $extra = []): void
    {
        Log::warning(self::LOG_TAG . " Fila #{$rowNumber} omitida: {$reason}", array_merge([
            'shipmentid' => $shipmentid,
        ], $extra));
    }

    /**
     * Valida la columna downloaddate y la normaliza al formato Y-m-d.
     *
     * Soporta los formatos típicos que entrega Excel:
     *  - Número serial de Excel (ej. 45727).
     *  - Instancias de DateTimeInterface.
     *  - Cadenas en formatos comunes (Y-m-d, d/m/Y, etc.).
     *
     * Devuelve null cuando el valor está vacío o no se puede interpretar como fecha.
     */
    private function parseDownloadDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            if ($value instanceof \DateTimeInterface) {
                return Carbon::instance(\DateTimeImmutable::createFromInterface($value))->format('Y-m-d');
            }

            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->format('Y-m-d');
            }

            $stringValue = trim((string) $value);
            if ($stringValue === '') {
                return null;
            }

            return Carbon::parse($stringValue)->format('Y-m-d');
        } catch (Throwable $e) {
            Log::warning(self::LOG_TAG . ' No se pudo interpretar downloaddate', [
                'value' => $value,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
