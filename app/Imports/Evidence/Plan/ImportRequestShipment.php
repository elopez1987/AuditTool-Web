<?php

namespace App\Imports\Evidence\Plan;

use App\Models\Evidence\StorageEvidenceProcess;
use App\Traits\Web\Evidence\CreateOrUpdateStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportRequestShipment implements ToCollection, WithHeadingRow
{
    use CreateOrUpdateStock;

    private $recordid;

    public function __construct($recordid)
    {
        $this->recordid = $recordid;
    }

    public function getShipment()
    {
        return StorageEvidenceProcess::findOrFail($this->recordid);
    }

    public function collection(Collection $rows)
    {
        $shipment = $this->getShipment();
        foreach ($rows as $row) {
            $partnumberid = $this->searchPartNumber($row['material'], $shipment);
            $this->createOrUpdatePartNumberStock($partnumberid, $row['quantity'], $shipment);
        }
    }
}
