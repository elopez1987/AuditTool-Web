<?php

namespace App\Imports\Shipments\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageShipmentBilling;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportShipmentBilling implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        $order = OrderDeliverie::controlAccessUser()->where('shipmentid', trim($row['shipmentid']))->first();
        if (! empty($order)) {
            $this->createNewImport($order, $row['billingdocument']);
        }
    }

    public function startRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 400;
    }

    public function chunkSize(): int
    {
        return 400;
    }

    public function rules(): array
    {
        return [
            '*.shipmentid' => 'required',
            '*.billingdocument' => 'required|max_digits:50',
        ];
    }

    public function mesage(): array
    {
        return [
            '*.billingdocument.required' => 'El campo documento de factura es requerido',
            '*.billingdocument.numeric' => 'El campo documento de factura debe ser numerico',
            '*.billingdocument.max_digits' => 'El campo documento de factura debe tener maximo 30 digitos',
            '*.billingdocument.regex' => 'El campo documento de factura debe tener solo numeros',
            '*.billingdocument.unique' => 'El documento de factura ya existe',
            '*.shipmentid.required' => 'El campo shipmentid es requerido',
            '*.shipmentid.exists' => 'El campo shipmentid no existe',
            '*.shipmentid.regex' => 'El campo shipmentid debe tener solo letras y numeros',
        ];
    }

    public static function createNewImport($order, $billingdocument)
    {
        StorageShipmentBilling::updateOrCreate([
            'centerid' => $order->centerid,
            'warehouseid' => $order->warehouseid,
            'orderid' => $order->id,
            'billingdocument' => trim((string) $billingdocument),
        ]);
    }
}
