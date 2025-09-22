<?php

namespace App\Imports\Shipments\VerifyProcess;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use App\Models\Shipments\ShipmentControl;
use App\Traits\Web\General\GetParameters;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ShipmentCheckImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation
{
    use GetParameters;

    public function model(array $row)
    {
        [$order, $sku] = $this->searchOrderAndSku($row);
        if (! empty($sku) && ! empty($order)) {
            ShipmentControl::updateOrCreate(
                [
                    'centerid' => $order->centerid,
                    'warehouseid' => $order->warehouseid,
                    'orderid' => $order->id,
                    'partnumberid' => $sku->id,
                ],
                [
                    'customerid' => $order->customerid,
                    'category1id' => $sku->category01id,
                    'category2id' => $sku->category02id,
                    'unitmeasureid' => $sku->stkuom ?? 'CS',
                    'quantityrequested' => $row['quantity'],
                    'quantityfail' => $row['quantity'],
                ]
            );
        }
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function rules(): array
    {
        return [
            '*.shipmentid' => ['required','regex:/^[A-Za-z0-9]{1,20}$/'],
//            '*.partnumberid' => ['required','regex:/^[A-Za-z0-9]{1,20}$/'],
            '*.partnumberid' => ['required'],
            '*.quantity' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            '*.shipmentid.exists' => 'El pedido no existe en el sistema',
            '*.partnumberid.exists' => 'El Sku no existe en el sistema',
            '*.quantity.integer' => 'La cantidad debe ser un numero entero',
            '*.quantity.required' => 'La cantidad es requerida',
            '*.shipmentid.regex' => 'El pedido debe contener solo letras y numeros',
            '*.partnumberid.regex' => 'El Sku debe contener solo letras y numeros',
            '*.quantity.regex' => 'La cantidad debe contener solo numeros',
            '*.quantity.required' => 'La cantidad es requerida',
        ];
    }

    /**
     * @param array $row
     *
     * @return array
     */
    public function searchOrderAndSku(array $row): array
    {
        static $lastShipmentId = null;
        static $lastOrder = null;
        static $lastPartnumberId = null;
        static $lastSku = null;
        $shipmentId = trim($row['shipmentid']);
        $partnumberId = trim($row['partnumberid']);

        if ($lastShipmentId !== $shipmentId) {
            $lastOrder = OrderDeliverie::controlAccessUser()->where('shipmentid', $shipmentId)
                ->select(['id', 'centerid', 'warehouseid', 'customerid'])->first();
            if (empty($lastOrder)) {
                LogImportFile::create([
                    'message' => 'Error al importar el pedido :' . $row['shipmentid'] . ' no existe en el sistema',
                    'created_at' => Carbon::now(),
                ]);
            }
            $lastShipmentId = $shipmentId;
        }
        $order = $lastOrder;

        if ($lastPartnumberId !== $partnumberId) {
            $lastSku = InventoryPartnumber::controlAccessUser()->where('prtnum', $partnumberId)
                ->select(['id', 'category01id', 'category02id', 'stkuom'])->first();
            if (! $lastSku) {
                LogImportFile::create([
                    'message' => 'Error al importar el Sku :' . $row['partnumberid'] . ' para el pedido :' . $row['shipmentid'] . ' no existe en el sistema',
                    'created_at' => Carbon::now(),
                ]);
            }
            $lastPartnumberId = $partnumberId;
        }
        $sku = $lastSku;
        return [$order, $sku];
    }
}
