<?php

namespace App\Imports\Inventario\PartNumbers;

use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Traits\Web\General\Responser;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPartNumbers implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use Responser;

    public function model(array $row)
    {
        $centerid = $this->getCenterForUser();
        $warehouseid = $this->getWarehouseForUser();
        $category01 = InventoryPartNumberCategory01::controlAccessUser()->first('id');
        $category02 = InventoryPartNumberCategory02::controlAccessUser()->first('id');
        $existingRecord = InventoryPartnumber::where(['centerid' => $centerid,
            'warehouseid' => $warehouseid, 'prtnum' => $row['prtnum'],])->first();
        $lngdsc = isset($row['lngdsc']) ? trim($row['lngdsc']) : ($existingRecord->lngdsc ?? '');
        InventoryPartnumber::updateOrCreate(
            [
                'centerid' => $centerid,
                'warehouseid' => $warehouseid,
                'prtnum' => $row['prtnum'],
            ],
            [
                'lngdsc' => $lngdsc,
                'abccod' => isset($row['abccod'])  ? trim($row['abccod']) : 'A',
                'shelflife' => isset($row['shelflife']) ? trim($row['shelflife']) : '0',
                'untcas' => isset($row['untcas']) ? trim($row['untcas']) : '1',
                'untpal' => isset($row['untpal']) ? trim($row['untpal']) : '1',
                'category01id' => isset($row['category01id']) ? trim($row['category01id']) : $category01->id,
                'category02id' => isset($row['category02id']) ? trim($row['category02id']) : $category02->id,
                'barcode' => isset($row['barcode']) ? $row['prtnum'] : '0',
                'auxiliary01' => isset($row['location']) ? trim($row['location']) : '0',
                'codeean' => isset($row['codeean']) ? trim($row['codeean']) : '0',
                'codedun' => isset($row['codedun']) ? trim($row['codedun']) : '0'
            ]
        );

    }

    public function headingRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }


}
