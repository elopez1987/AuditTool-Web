<?php

namespace App\Models\Scopes\Yard;

use App\Models\Security\Users\AccessOrigins;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class StorageUnitTypeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $username = Auth::user()?->username;

        // Sin usuario autenticado (jobs, console, etc.) no se aplica el filtro
        if (! $username) {
            return;
        }

        $connection = new AccessOrigins()->getConnectionName();

        // Evita el QueryException si la tabla no existe en esta conexión/tenant
        if (! Schema::connection($connection)->hasTable('securityaccessorigins')) {
            return;
        }

        $storageUnitTypeId = AccessOrigins::where('username', $username)
            ->pluck('storageunittype_id');

        // Si el usuario no tiene origen configurado, no se filtra
        if ($storageUnitTypeId->isEmpty()) {
            return;
        }

        $builder->whereIn($model->qualifyColumn('unittypeid'), $storageUnitTypeId);
    }
}
