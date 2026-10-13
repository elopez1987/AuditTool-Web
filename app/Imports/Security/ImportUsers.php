<?php

namespace App\Imports\Security;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Spatie\Permission\Models\Role;
use Throwable;

class ImportUsers implements SkipsEmptyRows, SkipsOnFailure, ToCollection, WithHeadingRow, WithValidation
{
    use Importable;
    use SkipsFailures;

    private const DEFAULT_PASSWORD = 'Temporal12##';

    private int $processed = 0;

    private int $succeeded = 0;

    private int $skipped = 0;

    /**
     * Role cache keyed by the raw roleid/name from the file.
     *
     * @var array<string, Role|null>
     */
    private array $roleCache = [];

    public function collection(Collection $rows): void
    {
        DB::table('logimports')->delete();

        $this->createLog('Inicio de importación de usuarios. Filas recibidas: ' . $rows->count());

        foreach ($rows as $index => $row) {
            $rowNumber = (int) $index + 2;
            $data = $row instanceof Collection ? $row->all() : (array) $row;

            if ($this->isBlankRow($data)) {
                continue;
            }

            $this->processed++;
            $username = trim((string) ($data['username'] ?? ''));

            $this->createLog("Fila #{$rowNumber}: iniciando importación de usuario [{$username}]");

            try {
                if ($username === '') {
                    $this->skipped++;
                    $this->createLog("Fila #{$rowNumber}: username vacío, se omite.");

                    continue;
                }

                $user = $this->upsertUser($data);
                $roleAssigned = $this->assignRole($data, $user, $rowNumber);
                $this->assignWarehouseAccess($data, $user);

                $this->succeeded++;
                $centerid = trim((string) ($data['centerid'] ?? ''));
                $warehouseid = trim((string) ($data['warehouseid'] ?? ''));
                $this->createLog(
                    "Fila #{$rowNumber}: usuario [{$user->username}] procesado correctamente"
                    . ($roleAssigned ? ' (rol asignado)' : ' (sin rol)')
                    . ", acceso {$centerid}/{$warehouseid}."
                );
            } catch (Throwable $exception) {
                $this->skipped++;
                $this->createLog(
                    "Fila #{$rowNumber}: error al importar [{$username}] - {$exception->getMessage()}"
                );
            }
        }

        $this->logValidationFailures();

        $this->createLog(sprintf(
            'Fin de importación de usuarios. Procesadas: %d | Exitosas: %d | Omitidas/Error: %d | Fallas validación: %d',
            $this->processed,
            $this->succeeded,
            $this->skipped,
            count($this->failures())
        ));
    }

    public function isEmptyWhen($row): bool
    {
        $data = $row instanceof Collection ? $row->all() : (array) $row;

        return $this->isBlankRow($data);
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function isBlankRow(array $row): bool
    {
        foreach (['centerid', 'warehouseid', 'username', 'name', 'email', 'roleid'] as $field) {
            $value = $row[$field] ?? null;
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function upsertUser(array $row): User
    {
        $username = trim((string) $row['username']);
        $email = strtolower(trim((string) $row['email']));
        $name = trim((string) $row['name']);

        $user = User::withTrashed()->updateOrCreate(
            [
                'username' => $username,
            ],
            [
                'email' => $email,
                'name' => $name,
                'statusid' => 'E',
            ]
        );

        if ($user->wasRecentlyCreated) {
            $user->forceFill([
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'password_changed_at' => null,
            ])->save();
        }

        if (method_exists($user, 'trashed') && $user->trashed()) {
            $user->restore();
            $user->statusid = 'E';
            $user->save();
        }

        return $user->fresh() ?? $user;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function assignRole(array $row, User $user, int $rowNumber): bool
    {
        $roleKey = trim((string) ($row['roleid'] ?? ''));
        if ($roleKey === '') {
            $this->createLog("Fila #{$rowNumber}: roleid vacío para [{$user->username}], se omite rol.");

            return false;
        }

        $role = $this->resolveRole($roleKey);
        if ($role === null) {
            $this->createLog(
                "Fila #{$rowNumber}: rol [{$roleKey}] no encontrado para [{$user->username}], se omite rol."
            );

            return false;
        }

        $user->syncRoles([$role->name]);

        return true;
    }

    private function resolveRole(string $roleKey): ?Role
    {
        if (array_key_exists($roleKey, $this->roleCache)) {
            return $this->roleCache[$roleKey];
        }

        // Prefer exact id match for numeric values to avoid SQL Server type issues.
        if (ctype_digit($roleKey)) {
            $role = Role::query()->where('id', (int) $roleKey)->first();
            if ($role === null) {
                $role = Role::query()->where('name', $roleKey)->first();
            }
        } else {
            $role = Role::query()->where('name', $roleKey)->first();
        }

        $this->roleCache[$roleKey] = $role;

        return $role;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function assignWarehouseAccess(array $row, User $user): void
    {
        SecurityAccessWarehouse::updateOrCreate(
            [
                'centerid' => trim((string) $row['centerid']),
                'warehouseid' => trim((string) $row['warehouseid']),
                'username' => (string) $user->username,
            ],
            [
                'defaultlevel' => 'S',
            ]
        );
    }

    private function logValidationFailures(): void
    {
        /** @var Failure $failure */
        foreach ($this->failures() as $failure) {
            $errors = implode('; ', $failure->errors());
            $username = $failure->values()['username'] ?? '';
            $this->createLog(
                "Validación fila #{$failure->row()}: [{$username}] {$failure->attribute()} - {$errors}"
            );
        }
    }

    private function createLog(string $message): void
    {
        LogImportFile::create([
            'message' => $message,
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function prepareForValidation($data, $index)
    {
        foreach (['username', 'email', 'name', 'roleid', 'centerid', 'warehouseid'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $data[$field] = trim((string) $data[$field]);
            }
        }

        if (isset($data['email'])) {
            $data['email'] = strtolower((string) $data['email']);
        }

        return $data;
    }

    public function rules(): array
    {
        return [
            '*.username' => ['required', 'string', 'min:4', 'max:100'],
            '*.email' => ['required', 'email', 'max:150'],
            '*.name' => ['required', 'string', 'max:150'],
            '*.roleid' => ['required', 'string', 'max:100'],
            '*.centerid' => ['required', 'string', 'exists:systemcenterdistributions,centerid'],
            '*.warehouseid' => ['required', 'string', 'exists:systemwarehouses,warehouseid'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.username.required' => 'El campo username es obligatorio.',
            '*.username.min' => 'El campo username debe tener al menos 4 caracteres.',
            '*.email.required' => 'El campo email es obligatorio.',
            '*.email.email' => 'El campo email no tiene un formato válido.',
            '*.name.required' => 'El campo name es obligatorio.',
            '*.roleid.required' => 'El campo roleid es obligatorio.',
            '*.centerid.required' => 'El campo centerid es obligatorio.',
            '*.centerid.exists' => 'El centerid no existe en centros de distribución.',
            '*.warehouseid.required' => 'El campo warehouseid es obligatorio.',
            '*.warehouseid.exists' => 'El warehouseid no existe en bodegas.',
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @return array{processed: int, succeeded: int, skipped: int, failures: int}
     */
    public function summary(): array
    {
        return [
            'processed' => $this->processed,
            'succeeded' => $this->succeeded,
            'skipped' => $this->skipped,
            'failures' => count($this->failures()),
        ];
    }
}
