<?php

namespace App\Models\Security\Modules;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $role_id
 * @property string $model_type
 * @property int $model_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelHasRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelHasRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelHasRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelHasRole whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelHasRole whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelHasRole whereRoleId($value)
 *
 * @mixin \Eloquent
 */
class ModelHasRole extends Model
{
    protected $table = 'model_has_roles';

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];
}
