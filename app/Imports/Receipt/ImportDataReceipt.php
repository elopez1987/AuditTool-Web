<?php

namespace App\Imports\Receipt;

use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptStock;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportDataReceipt implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation
{
    use Importable;


    protected $receipt;

    public function __construct($receipt)
    {
        $this->receipt = $receipt;
    }

    public function model(array $row)
    {
        $partnumberid = InventoryPartNumber::controlAccessUser()->where('prtnum', trim($row['sku']))->first();
        $category = InventoryStockCategory::where('stockcategoryid',$row['category'])->first();
        ReceiptStock::updateOrCreate([
            'centerid' => $this->receipt->centerid,
            'warehouseid' => $this->receipt->warehouseid,
            'receiptid' => $this->receipt->id,
            'partnumberid' => $partnumberid->id,
            'expirecode' => trim($row['batch']),
            'quantitytrue' => floatval($row['quantity']),
        ], [
            'shipmentid' => $this->receipt->shipmentid,
            'category01id' => $partnumberid->category01id,
            'category02id' => $partnumberid->category02id,
            'productiondate' => \Carbon\Carbon::now()->format('Y-m-d'),
            'stockcategoryid' => $category->id,
            'expiredate' => \Carbon\Carbon::now()->addDays($partnumberid->shelflife)->format('Y-m-d')
        ]);
    }

//shelflife


    // Si el archivo tiene encabezados, empieza a leer desde la segunda fila
    public function startRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }


    public function rules(): array
    {
        return [
            '*.sku' => [
//                'string',
                'required'
            ],
            '*.quantity' => [
                'numeric',
                'required'
            ],
            '*.batch' => [
//                'string',
                'required'
            ],
            '*.category' => [
//                'string',
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            '*.quantity.required' => 'El campo quantity es obligatorio para cada artículo.',
            '*.quantity.numeric' => 'El campo quantity debe ser numérico para cada artículo.',
            '*.quantity.min' => 'El campo quantity debe ser al menos 1 para cada artículo.',

        ];
    }

}
