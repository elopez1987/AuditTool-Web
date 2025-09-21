<?php

namespace App\Imports\Shipments\OrderDelivery;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportCommentShipment implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        $order = OrderDeliverie::controlAccessUser()
            ->where('shipmentid', trim($row['shipmentid']))
            ->whereNotIn('statusid', ['CA', 'DH'])->first();
        if (! empty($order)) {
            $this->createNewImport($order, $row['comment']);
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

    public static function createNewImport($order, $comment)
    {
        ShipmentDataOperation::updateOrCreate([
            'orderid' => $order->id,
        ], [
            'comment02' => $comment,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.shipmentid' => 'required|exists:orderdeliveries,shipmentid',
            '*.comment' => 'required',
        ];
    }
}
