<?php

namespace App\Imports\Security;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;
use Throwable;

class ImportUsers implements SkipsOnError, SkipsOnFailure, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    use Importable;
    use SkipsErrors;
    use SkipsFailures;

    /**
     * Contadores para tener un resumen al final del import.
     */
    protected int $processedRows = 0;

    protected int $createdOrUpdatedUsers = 0;

    protected int $skippedRows = 0;

    public function model(array $row)
    {
        $this->processedRows++;
        $rowContext = $this->safeContext($row);

        Log::info('[ImportUsers] Procesando fila', $rowContext);

        // NOTA: No envolvemos en DB::transaction() para evitar el error
        // "Cannot roll back transNN. No transaction or savepoint of that name was found."
        // que ocurre con SQL Server cuando se crean savepoints anidados dentro de
        // los chunks de Maatwebsite/Excel. Los updateOrCreate ya son atómicos.
        try {
            $user = $this->getUpdateOrCreateUser($row);
            $this->assignRole($row, $user);
            $this->createAccessWarehouse($row, $user);

            $this->createdOrUpdatedUsers++;

            Log::info('[ImportUsers] Fila procesada correctamente', $rowContext + [
                'userid' => $user->id,
            ]);
        } catch (Throwable $e) {
            $this->skippedRows++;

            Log::error('[ImportUsers] Error procesando fila', $rowContext + [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        // Devolvemos null porque la persistencia la manejamos manualmente
        // con updateOrCreate para evitar duplicados.

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
            '*.username' => ['required', 'string', 'max:100'],
            '*.email' => ['required', 'email', 'max:150'],
            '*.name' => ['required', 'string', 'max:100'],
            '*.roleid' => ['required'],
            '*.centerid' => [
                'required',
                'string',
                'exists:systemcenterdistributions,centerid',
            ],
            '*.warehouseid' => [
                'required',
                'string',
                'exists:systemwarehouses,warehouseid',
            ],
        ];
    }

    /**
     * Normaliza la fila antes de ejecutar la validación.
     * Esto permite que usernames numéricos (p.ej. 10089727) no fallen la regla "string".
     */
    public function prepareForValidation($row, $index)
    {
        foreach (['username', 'email', 'name', 'roleid', 'centerid', 'warehouseid'] as $field) {
            if (array_key_exists($field, $row) && $row[$field] !== null) {
                $row[$field] = (string) $row[$field];
            }
        }

        return $row;
    }

    public function customValidationMessages(): array
    {
        return [
            '*.username.required' => 'El campo username es obligatorio.',
            '*.email.required' => 'El campo email es obligatorio.',
            '*.email.email' => 'El campo email no tiene un formato válido.',
            '*.name.required' => 'El campo name es obligatorio.',
            '*.roleid.required' => 'El campo roleid es obligatorio.',
            '*.centerid.required' => 'El campo centerid es obligatorio.',
            '*.centerid.exists' => 'El centerid no existe en el catálogo de centros de distribución.',
            '*.warehouseid.required' => 'El campo warehouseid es obligatorio.',
            '*.warehouseid.exists' => 'El warehouseid no existe en el catálogo de bodegas.',
        ];
    }

    /**
     * Resumen final del import para debug.
     */
    public function summary(): array
    {
        return [
            'processed' => $this->processedRows,
            'created_or_updated' => $this->createdOrUpdatedUsers,
            'skipped' => $this->skippedRows,
            'failures' => count($this->failures()),
            'errors' => count($this->errors()),
        ];
    }

    protected function getUpdateOrCreateUser(array $row): User
    {
        return User::updateOrCreate(
            [
                'username' => strtolower(trim((string) ($row['username'] ?? ''))),
            ],
            [
                'email' => strtolower(trim((string) ($row['email'] ?? ''))),
                'name' => trim((string) ($row['name'] ?? '')),
                'password' => bcrypt('Temporal12##'),
            ]
        );
    }

    protected function assignRole(array $row, User $user): void
    {
        $roleid = trim((string) ($row['roleid'] ?? ''));

        if ($roleid === '') {
            Log::warning('[ImportUsers] roleid vacío, se omite asignación de rol', [
                'username' => $user->username,
            ]);

            return;
        }

        $role = Role::query()
            ->where('id', $roleid)
            ->orWhere('name', $roleid)
            ->first();

        if ($role === null) {
            Log::warning('[ImportUsers] Rol no encontrado, se omite asignación', [
                'username' => $user->username,
                'roleid' => $roleid,
            ]);

            return;
        }

        $user->syncRoles([$role->id]);
    }

    protected function createAccessWarehouse(array $row, User $user): void
    {
        SecurityAccessWarehouse::updateOrCreate(
            [
                'centerid' => trim((string) ($row['centerid'] ?? '')),
                'warehouseid' => trim((string) ($row['warehouseid'] ?? '')),
                'username' => (string) $user->username,
            ],
            []
        );
    }

    /**
     * Devuelve un subconjunto seguro de datos de la fila para loguear,
     * evitando exponer datos sensibles (p.ej. password).
     */
    protected function safeContext(array $row): array
    {
        return [
            'username' => $row['username'] ?? null,
            'email' => $row['email'] ?? null,
            'roleid' => $row['roleid'] ?? null,
            'centerid' => $row['centerid'] ?? null,
            'warehouseid' => $row['warehouseid'] ?? null,
        ];
    }
}
