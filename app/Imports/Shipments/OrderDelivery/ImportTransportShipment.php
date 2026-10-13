<?php

namespace App\Imports\Shipments\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Traits\Web\General\Responser;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class ImportTransportShipment implements SkipsEmptyRows, ToCollection, WithHeadingRow, WithValidation
{
    use Responser;

    private const DATE_FORMAT = 'Y-m-d H:i';

    private const DATE_FORMAT_REGEX = '/^\d{4}-\d{2}-\d{2} \d{1,2}:\d{2}$/';

    /**
     * Import timestamp used as the baseline for arrival_date validation.
     */
    private Carbon $importedAt;

    /**
     * Cached center for the authenticated user.
     */
    private mixed $centerid = null;

    /**
     * Cached warehouse for the authenticated user.
     */
    private mixed $warehouseid = null;

    /**
     * In-memory cache of carriers keyed by center|carrier code.
     *
     * @var array<string, ShipmentCarrier>
     */
    private array $carrierCache = [];

    public function __construct()
    {
        // Baseline in app timezone so Excel wall-clock dates compare correctly.
        $this->importedAt = Carbon::now(config('app.timezone'));
    }

    public function collection(Collection $rows): void
    {
        DB::table('logimports')->delete();

        foreach ($rows as $row) {
            // Defensive skip for blank trailing rows that may still arrive.
            if ($this->isBlankRow($row)) {
                continue;
            }

            $shipmentid = trim((string) ($row['shipment'] ?? ''));

            $this->createLogImport(
                "Iniciando importación de transporte para el embarque: {$shipmentid}"
            );

            if ($shipmentid === '') {
                $this->createLogImport('El número de embarque es obligatorio.');

                continue;
            }

            $order = OrderDeliverie::controlAccessUser()
                ->where('orderdeliveries.shipmentid', $shipmentid)
                ->where('orderdeliveries.statusid', '!=', 'DH')
                ->first();

            if ($order === null) {
                $this->createLogImport(
                    "El embarque {$shipmentid} no existe o tiene estatus DH."
                );

                continue;
            }

            $arrivalDate = $this->parseDateField(
                $row['arrival_date'] ?? null,
                'arrival_date',
                $shipmentid
            );
            if ($arrivalDate === null) {
                continue;
            }

            $appointmentDate = $this->parseDateField(
                $row['appointment_date'] ?? null,
                'appointment_date',
                $shipmentid
            );
            if ($appointmentDate === null) {
                continue;
            }

            if ($arrivalDate->lte($this->importedAt)) {
                $this->createLogImport(
                    "El embarque {$shipmentid}: arrival_date ({$arrivalDate->format(self::DATE_FORMAT)}) debe ser mayor a la fecha de importación ({$this->importedAt->format(self::DATE_FORMAT)})."
                );

                continue;
            }

            if (! $arrivalDate->lt($appointmentDate)) {
                $this->createLogImport(
                    "El embarque {$shipmentid}: arrival_date ({$arrivalDate->format(self::DATE_FORMAT)}) debe ser menor a appointment_date ({$appointmentDate->format(self::DATE_FORMAT)}), y appointment_date debe ser mayor a la fecha de carga."
                );

                continue;
            }

            $carrierCode = trim((string) ($row['carrier'] ?? ''));
            $carrierName = trim((string) ($row['name'] ?? ''));

            if ($carrierCode === '') {
                $this->createLogImport(
                    "El embarque {$shipmentid}: la columna carrier es obligatoria."
                );

                continue;
            }

            if ($carrierName === '') {
                $this->createLogImport(
                    "El embarque {$shipmentid}: la columna name es obligatoria para la línea de transporte."
                );

                continue;
            }

            $carrier = $this->resolveOrCreateCarrier(
                $carrierCode,
                $carrierName,
                $order->centerid,
                $order->warehouseid,
                $shipmentid
            );

            if ($carrier === null) {
                continue;
            }

            try {
                $order->update([
                    'carrierid' => $carrier->id,
                    'loadingdate' => $arrivalDate->format(self::DATE_FORMAT),
                    'downloaddate' => $appointmentDate->format(self::DATE_FORMAT),
                ]);

                $this->createLogImport(
                    "Embarque {$shipmentid} actualizado correctamente. Carrier: {$carrierCode}, loadingdate: {$arrivalDate->format(self::DATE_FORMAT)}, downloaddate: {$appointmentDate->format(self::DATE_FORMAT)}."
                );
            } catch (Throwable $exception) {
                $this->createLogImport(
                    "Error al actualizar el embarque {$shipmentid}: {$exception->getMessage()}"
                );
            }
        }
    }

    /**
     * Treat a row as empty when all relevant cells are blank.
     * Used by Maatwebsite when SkipsEmptyRows is implemented with isEmptyWhen().
     *
     * @param  array<string, mixed>|Collection  $row
     */
    public function isEmptyWhen($row): bool
    {
        return $this->isBlankRow($row);
    }

    /**
     * @param  array<string, mixed>|Collection  $row
     */
    private function isBlankRow($row): bool
    {
        $data = $row instanceof Collection ? $row->all() : (array) $row;

        foreach (['shipment', 'carrier', 'name', 'arrival_date', 'appointment_date'] as $field) {
            $value = $data[$field] ?? null;
            if ($value !== null && $value !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * Parse and validate a date field. Expected format: Y-m-d H:i (aaaa-mm-dd H:mm).
     * Also accepts Excel serial dates and DateTime instances.
     *
     * Excel serials from PhpSpreadsheet are UTC wall-clock values; we re-create
     * them in the app timezone so 18:30 means 18:30 America/Mexico_City.
     */
    private function parseDateField(mixed $value, string $field, string $shipmentid): ?Carbon
    {
        if ($value === null || $value === '') {
            $this->createLogImport(
                "El embarque {$shipmentid}: el campo {$field} es obligatorio y debe tener formato aaaa-mm-dd H:mm."
            );

            return null;
        }

        try {
            $timezone = (string) config('app.timezone');

            if ($value instanceof CarbonInterface || $value instanceof DateTimeInterface) {
                return $this->toAppTimezoneWallClock($value, $timezone);
            }

            if (is_numeric($value)) {
                // Excel stores local wall-clock as a serial; PhpSpreadsheet returns UTC.
                $excelDate = ExcelDate::excelToDateTimeObject((float) $value);

                return $this->toAppTimezoneWallClock($excelDate, $timezone);
            }

            $raw = trim((string) $value);

            if (! preg_match(self::DATE_FORMAT_REGEX, $raw)) {
                $this->createLogImport(
                    "El embarque {$shipmentid}: el campo {$field} ({$raw}) no cumple el formato aaaa-mm-dd H:mm."
                );

                return null;
            }

            // Normalize single-digit hour (e.g. 7:30 -> 07:30) for strict parsing.
            $normalized = preg_replace_callback(
                '/^(\d{4}-\d{2}-\d{2}) (\d{1,2}):(\d{2})$/',
                static function (array $matches): string {
                    return sprintf(
                        '%s %02d:%s',
                        $matches[1],
                        (int) $matches[2],
                        $matches[3]
                    );
                },
                $raw
            );

            return Carbon::createFromFormat(self::DATE_FORMAT, $normalized, $timezone)->startOfMinute();
        } catch (Throwable) {
            $this->createLogImport(
                "El embarque {$shipmentid}: el campo {$field} no es una fecha válida. Use formato aaaa-mm-dd H:mm."
            );

            return null;
        }
    }

    /**
     * Rebuild a datetime using its displayed Y-m-d H:i components in app timezone.
     * Avoids false failures when Excel/UTC instants are compared with local now().
     */
    private function toAppTimezoneWallClock(DateTimeInterface $value, string $timezone): Carbon
    {
        $wallClock = $value->format('Y-m-d H:i');

        return Carbon::createFromFormat(self::DATE_FORMAT, $wallClock, $timezone)->startOfMinute();
    }

    /**
     * Find carrier by carrierid or create it with the provided name.
     */
    private function resolveOrCreateCarrier(
        string $carrierCode,
        string $carrierName,
        mixed $centerid,
        mixed $warehouseid,
        string $shipmentid
    ): ?ShipmentCarrier {
        $cacheKey = trim((string) $centerid) . '|' . $carrierCode;

        if (isset($this->carrierCache[$cacheKey])) {
            return $this->carrierCache[$cacheKey];
        }

        try {
            $center = $centerid ?: $this->resolveCenterId();
            $warehouse = $warehouseid ?: $this->resolveWarehouseId();

            $carrier = ShipmentCarrier::query()
                ->where('carrierid', $carrierCode)
                ->when($center, fn ($query) => $query->where('centerid', $center))
                ->first();

            if ($carrier === null) {
                $carrier = ShipmentCarrier::create([
                    'centerid' => $center,
                    'warehouseid' => $warehouse,
                    'carrierid' => $carrierCode,
                    'name' => $carrierName,
                ]);

                $this->createLogImport(
                    "Embarque {$shipmentid}: se creó la línea de transporte carrierid={$carrierCode}, name={$carrierName}."
                );
            }

            $this->carrierCache[$cacheKey] = $carrier;

            return $carrier;
        } catch (Throwable $exception) {
            $this->createLogImport(
                "Embarque {$shipmentid}: no se pudo resolver/crear el carrier {$carrierCode}. {$exception->getMessage()}"
            );

            return null;
        }
    }

    private function resolveCenterId(): mixed
    {
        return $this->centerid ??= $this->getCenterForUser();
    }

    private function resolveWarehouseId(): mixed
    {
        return $this->warehouseid ??= $this->getWarehouseForUser();
    }

    private function createLogImport(string $message): void
    {
        LogImportFile::create([
            'message' => $message,
            'created_at' => Carbon::now(),
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {
        return [
            '*.shipment' => ['required'],
            '*.carrier' => ['required'],
            '*.name' => ['required'],
            '*.arrival_date' => ['required'],
            '*.appointment_date' => ['required'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.shipment.required' => 'El campo shipment (número de embarque) es obligatorio.',
            '*.carrier.required' => 'El campo carrier es obligatorio.',
            '*.name.required' => 'El campo name es obligatorio.',
            '*.arrival_date.required' => 'El campo arrival_date es obligatorio (formato aaaa-mm-dd H:mm).',
            '*.appointment_date.required' => 'El campo appointment_date es obligatorio (formato aaaa-mm-dd H:mm).',
        ];
    }
}
