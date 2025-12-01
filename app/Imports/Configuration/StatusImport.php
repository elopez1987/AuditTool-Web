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
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        SystemStatus::updateOrCreate(
            [
                'schemaid' => $this->schema->schemaid,
                'statusid' => trim($row['statusid']),
            ], [
                'name' => trim($row['name']),
            ],
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
            '*.statusid' => [
                'required',
            ],
            '*.name' => [
                'required',
            ],
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
