<?php

namespace App\Imports\Yard\CheckList;

use App\Models\Yard\StorageCheckListDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportCheckListDetails implements ToCollection, WithHeadingRow, WithValidation
{
    protected $checklist;

    public function __construct($checklist)
    {
        $this->checklist = $checklist;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            StorageCheckListDetail::updateOrCreate(
                [
                    'centerid' => $this->checklist->centerid,
                    'checklistid' => $this->checklist->id,
                    'description' => trim($row['name']),
                ],
                [
                    'auxiliary01' => trim($row['id']),
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.id' => ['required','int'],
            '*.name' => ['required', 'string', 'max:255'],
        ];
    }
}
