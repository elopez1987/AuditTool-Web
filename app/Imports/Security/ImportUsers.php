<?php

namespace App\Imports\Security;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportUsers implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        $user = $this->getUpdateOrCreateUser($row);
        $user->syncRoles([$row['roleid']]);
        $this->createAccessWarehouse($row, $user);
    }

    public function startRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 350;
    }

    public function chunkSize(): int
    {
        return 350;
    }

    public function rules(): array
    {
        return [
            '*.username' => [
                'string',
                'required',
            ],
            '*.email' => [
                'email',
                'required',
            ],
            '*.name' => [
                'string',
                'required',
                'max:100',
            ],
            '*.centerid' => [
                'string',
                'required',
                'exists:systemcenterdistributions,centerid',
            ],
            '*.warehouseid' => [
                'string',
                'required',
                'exists:systemwarehouses,warehouseid',
            ],
        ];
    }

    public function getUpdateOrCreateUser(array $row): User
    {
        return User::updateOrCreate(
            [
                'username' => $row['username'],
            ],
            [
                'email' => $row['email'],
                'name' => $row['name'],
                'password' => bcrypt('Temporal12##'),
            ]
        );
    }

    public function createAccessWarehouse(array $row, User $user): void
    {
        SecurityAccessWarehouse::create([
            'centerid' => $row['centerid'],
            'warehouseid' => $row['warehouseid'],
            'username' => (string) $user->username,
        ]);
    }
}
