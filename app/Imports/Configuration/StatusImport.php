<?php

namespace App\Imports\Configuration;

use App\Models\Configuration\Schemas\SystemStatus;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StatusImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    use Importable;

    protected $schema;

    public function __construct($schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return void
     */
    public function model(array $row)
    {
        $data = [
            'name' => trim($row['name']),
        ];

        $optionalFields = [
            'centerid',
            'warehouseid',
            'comment',
            'auxiliary01',
            'auxiliary02',
            'auxiliary03',
            'auxiliary04',
            'auxiliary05',
        ];

        foreach ($optionalFields as $field) {
            if (array_key_exists($field, $row)) {
                $data[$field] = trim($row[$field]) ?? '';
            }
        }

        SystemStatus::updateOrCreate(
            [
                'schemaid' => $this->schema->schemaid,
                'statusid' => trim($row['statusid']),
            ],
            $data,
        );
    }

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
            '*.statusid' => 'required',
            '*.name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            '*.name.required' => 'El campo nombre es obligatorio para cada artículo.',
            '*.statusid.required' => 'El campo statusid es obligatorio.',
        ];
    }
}
