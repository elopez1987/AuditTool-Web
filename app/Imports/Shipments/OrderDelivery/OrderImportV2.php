<?php

namespace App\Imports\Shipments\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use App\Models\Shipments\OrderdeliveryTrace;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;
use App\Traits\Web\Shipments\OrderDelivery\CreateNewOrder;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OrderImportV2 implements ToCollection, WithHeadingRow, WithValidation
{
    use CreateNewOrder;
    use GetParameters;
    use Responser;

    /**
     * Resolved center id for the authenticated user (cached per import run).
     */
    private mixed $centerid = null;

    /**
     * Resolved warehouse id for the authenticated user (cached per import run).
     */
    private mixed $warehouseid = null;

    /**
     * In-memory cache of customers keyed by shipToId to avoid repeated queries.
     *
     * @var array<string, OrderCustomer|null>
     */
    private array $customerCache = [];

    /**
     * In-memory cache of carriers keyed by carrierId to avoid repeated queries.
     *
     * @var array<string, ShipmentCarrier|null>
     */
    private array $carrierCache = [];

    public function validateDate($workday)
    {
        $valores = explode('/', $workday);
        if (count($valores) === 3 && checkdate($valores[0], $valores[2], $valores[1])) {
            return true;
        }

        return false;
    }

    public function createlogimportshipment($message)
    {
        LogImportFile::create([
            'message' => $message,
            'created_at' => Carbon::now(),
        ]);
    }

    public function collection(Collection $rows)
    {
        DB::table('logimports')->delete();
        foreach ($rows as $row) {
            $weight = $row->get('weight');
            if ($weight !== null && $weight !== '' && ! is_numeric($weight)) {
                $this->createlogimportshipment('El peso debe de ser un valor númerico para el embarque: ' . $weight);

                continue;
            }
            $pallets = $row->get('pallets');
            if ($pallets !== null && $pallets !== '' && filter_var($pallets, FILTER_VALIDATE_INT) === false) {
                $this->createlogimportshipment('El valor de los pallets debe ser un entero: ' . $pallets);

                continue;
            }
            $picking = $row->get('picking');
            if ($picking !== null && $picking !== '' && filter_var($picking, FILTER_VALIDATE_INT) === false) {
                $this->createlogimportshipment('El valor de picking debe de ser entero: ' . $picking);

                continue;
            }
            $dateWorkDay = Carbon::createFromFormat('d/m/Y', $row['workday'])->format('Y-m-d');
            $loadingDate = Carbon::createFromFormat('d/m/Y H:i', $row['loadingdate'])->format('Y-m-d');
            $downLoadDate = Carbon::createFromFormat('d/m/Y H:i', $row['appointdate'])->format('Y-m-d');
            if ($loadingDate < $dateWorkDay) {
                $this->createlogimportshipment('La fecha de carga no puede ser menor a la de trabajo: ' . $dateWorkDay . '|' . $loadingDate);

                continue;
            }
            if ($downLoadDate < $loadingDate) {
                $this->createlogimportshipment('La fecha de entrega no puede ser menor a la fecha de carga: ' . $downLoadDate . '|' . $loadingDate);

                continue;
            }
            if ($downLoadDate < $dateWorkDay) {
                $this->createlogimportshipment('La fecha de entrega no puede ser menor a la fecha de trabajo: ' . $downLoadDate . '|' . $dateWorkDay);

                continue;
            }
            $customer = $this->resolveCustomer((string) $row['customerid']);
            if ($customer === null) {
                $this->createlogimportshipment('No existe el número de cliente :' . $row['customerid'] . ' para el embarque : ' . $row['shipmentid']);

                continue;
            }
            $carrier = $this->resolveCarrier((string) $row['carrierid']);
            if ($carrier === null) {
                $this->createlogimportshipment('No existe el número de linea de transporte:' . $row['carrierid'] . ' para el embarque : ' . $row['shipmentid']);

                continue;
            }
            $shipment = $this->createNewOrderDeliveryForPlan($row, $carrier, $customer);
            OrderdeliveryTrace::saveEventShipment($shipment, 'EEM-01', 'AP');
            self::createNewRegisterHeaderForImport($shipment, $row);
            $this->createNewOrder($shipment, $row, '');
        }
    }

    /**
     * Resolve and cache the center id for the authenticated user.
     */
    private function resolveCenterId(): mixed
    {
        return $this->centerid ??= $this->getCenterForUser();
    }

    /**
     * Resolve and cache the warehouse id for the authenticated user.
     */
    private function resolveWarehouseId(): mixed
    {
        return $this->warehouseid ??= $this->getWarehouseForUser();
    }

    /**
     * Resolve a customer by its shipToId, caching the result for the whole
     * import to avoid repeated queries for shipments of the same customer.
     */
    private function resolveCustomer(string $customerid): ?OrderCustomer
    {
        $key = trim($customerid);
        if (! array_key_exists($key, $this->customerCache)) {
            $this->customerCache[$key] = OrderCustomer::where('shiptoid', '=', $key)
                ->whereCenterid($this->resolveCenterId())
                ->first(['id']);
        }

        return $this->customerCache[$key];
    }

    /**
     * Resolve a carrier by its carrierId, caching the result for the whole
     * import to avoid repeated queries for shipments of the same carrier.
     */
    private function resolveCarrier(string $carrierid): ?ShipmentCarrier
    {
        $key = trim($carrierid);
        if (! array_key_exists($key, $this->carrierCache)) {
            $this->carrierCache[$key] = ShipmentCarrier::where('carrierid', '=', $key)
                ->whereCenterid($this->resolveCenterId())
                ->first(['id']);
        }

        return $this->carrierCache[$key];
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {
        return [
            '*.workday' => ['required'],
            '*.shipmentid' => ['required'],
            '*.customerid' => ['required'],
            '*.turnid' => ['required'],
            '*.loadingdate' => ['required'],
            '*.appointdate' => ['required'],
            '*.carrierid' => ['required'],

            '*.confirmation' => ['nullable'],
            '*.dvbusiness' => ['nullable'],
            '*.responsible' => ['nullable'],
            '*.pallets' => ['nullable', 'int', 'max_digits:20'],
            '*.picking' => ['nullable', 'int', 'max_digits:20'],
            '*.weight' => ['nullable', 'numeric', 'max_digits:50'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.workday.required' => 'El campo fecha de trabajo es obligatorio.',
            '*.shipmentid.required' => 'El campo numero de embarque es obligatorio.',
            '*.customerid.required' => 'El campo numero de cliente es obligatorio.',
            '*.turnid.required' => 'El campo turno es obligatorio.',
            '*.loadingdate.required' => 'El campo fecha de carga es obligatorio.',
            '*.appointdate.required' => 'El campo fecha de entrega es obligatorio.',
        ];
    }

    public static function createNewRegisterHeaderForImport($shipment, $row): void
    {
        ShipmentHeader::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
            ],
            [
                'adddate' => $shipment->created_at,
                'totalweight' => $row['weight'] ?? '',
                'totalcontainers' => $row['pallets'] ?? '',
                'totalpieces' => $row['picking'] ?? ' ',
            ],
        );
    }

    private function createNewOrderDeliveryForPlan($row, ShipmentCarrier $carrier, OrderCustomer $customer): OrderDeliverie
    {
        return OrderDeliverie::updateOrCreate(
            [
                'centerid' => $this->resolveCenterId(),
                'warehouseid' => $this->resolveWarehouseId(),
                'shipmentid' => trim((string) $row['shipmentid']),
            ],
            [
                'workday' => Carbon::createFromFormat('d/m/Y', $row['workday'])->format('Y-m-d'),
                'turnid' => $row['turnid'],
                'carrierid' => $carrier->id,
                'customerid' => $customer->id,
                'loadingdate' => Carbon::createFromFormat('d/m/Y H:i', $row['loadingdate'])->format('Y-m-d H:i'),
                'downloaddate' => Carbon::createFromFormat('d/m/Y H:i', $row['appointdate'])->format('Y-m-d H:i'),

                'shipmenttypeid' => '1',
                'auxiliary01' => (string) $row->get('dvbusiness', ''),
                'auxiliary02' => (string) $row->get('responsible', ''),
                'confirmation' => (string) $row->get('confirmation', ''),
            ],
        );
    }
}
