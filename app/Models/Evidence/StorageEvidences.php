<?php

namespace App\Models\Evidence;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Evidence\StorageEvidences
 *
 * @property string $centerid
 * @property string|null $warehouseid
 * @property int $id
 * @property string $modelid
 * @property string $modeltype
 * @property string $path
 * @property string|null $username
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereModelid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereModeltype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageEvidences whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageEvidences extends Model
{
    protected $table = 'storageevidences';

    protected $primaryKey = 'id';

    protected $keyType = 'integer';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'modelid',
        'modeltype',
        'path',
        'username',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageEvidences $model) {
                $model->username = Auth::user()->username;
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageEvidences::whereIn('storageevidences.centerid', $centers);
    }

    public static function createNewWvidence($model, $path, $modeltype, $modelid)
    {
        StorageEvidences::create([
            'centerid' => $model->centerid,
            'warehouseid' => $model->warehouseid,
            'modelid' => $modelid,
            'modeltype' => $modeltype,
            'path' => $path,
        ]);
    }
}
