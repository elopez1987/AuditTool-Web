<?php

namespace App\Models\Evidence;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Evidence\StorageMasterDetail
 *
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $masterid
 * @property string|null $lpnid
 * @property string $scanuser
 * @property string $scandate
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail whereMasterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail whereScandate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail whereScanuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterDetail whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageMasterDetail extends Model
{
    public $timestamps = false;

    protected $table = 'storagemasterdetailevidence';

    protected $fillable = [
        'centerid', 'warehouseid', 'masterid', 'lpnid', 'scanuser', 'scandate',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageMasterDetail $model) {
                $model->scanuser = Auth::user()->username;
                $model->scandate = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageMasterDetail::whereIn('storagemasterdetailevidence.warehouseid', $warehouses)
            ->whereIn('storagemasterdetailevidence.centerid', $centers);
    }
}
