<?php

namespace App\Imports\Shipments\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderDelivery\LogImportFile;
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

class OrderImport implements ToCollection, WithHeadingRow, WithValidation
{
    use CreateNewOrder;
    use GetParameters;
    use Responser;

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
        $centerid = $this->getCenterForUser();
        DB::table('logimports')->delete();
        foreach ($rows as $row) {
            if ($row['confirmation'] === '') {
                $this->createlogimportshipment('El numero de embarque no cuenta con numero de confirmación : ' . $row['confirmation']);

                continue;
            }
            if (isset($row['weight'])) {
                $typeWeight = is_numeric($row['weight']);
                if (! $typeWeight) {
                    $this->createlogimportshipment('El peso debe de ser un valor númerico para el embarque: ' . $row['weight']);

                    continue;
                }
            }
            if (isset($row['pallets'])) {
                $typePallets = is_int($row['pallets']);
                if (! $typePallets) {
                    $this->createlogimportshipment('El valor de los pallets debe ser un entero: ' . $row['pallets']);

                    continue;
                }
            }
            if (isset($row['picking'])) {
                $typePicking = is_int($row['picking']);
                if (! $typePicking) {
                    $this->createlogimportshipment('El valor de picking debe de ser entero: ' . $row['picking']);

                    continue;
                }
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
            $customer = OrderCustomer::where('shiptoid', '=', trim($row['customerid']))->whereCenterid($centerid)->first(['id']);
            if ($customer === null) {
                $this->createlogimportshipment('No existe el número de cliente :' . $row['customerid'] . ' para el embarque : ' . $row['shipmentid']);

                continue;
            }
            $carrier = ShipmentCarrier::where('carrierid', '=', trim($row['carrierid']))->whereCenterid($centerid)->first(['id']);
            if ($carrier === null) {
                $this->createlogimportshipment('No existe el número de linea de transporte:' . $row['carrierid'] . ' para el embarque : ' . $row['shipmentid']);

                continue;
            }
            $shipment = self::createNewOrderDeliveryForPlan($row, $carrier, $customer);
            self::createNewRegisterHeaderForImport($shipment, $row);
            $this->createNewOrder($shipment, $row, '');
        }
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
            '*.confirmation' => ['required'],

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
                'centerid' => $this->getCenterForUser(),
                'warehouseid' => $this->getWarehouseForUser(),
                'shipmentid' => trim((string) $row['shipmentid']),
            ],
            [
                'workday' => Carbon::createFromFormat('d/m/Y', $row['workday'])->format('Y-m-d'),
                'turnid' => $row['turnid'],
                'carrierid' => $carrier->id,
                'customerid' => $customer->id,
                'shipmenttypeid' => '3',
                'auxiliary01' => (string) $row['dvbusiness'] ?? '',
                'auxiliary02' => (string) $row['responsible'] ?? '',
                'loadingdate' => Carbon::createFromFormat('d/m/Y H:i', $row['loadingdate'])->format('Y-m-d H:i'),
                'downloaddate' => Carbon::createFromFormat('d/m/Y H:i', $row['appointdate'])->format('Y-m-d H:i'),
                'confirmation' => (string) $row['confirmation'],
            ],
        );
    }
}
