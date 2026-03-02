<?php

namespace App\Models\Evidence;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Evidence\StorageMasterEvidence
 *
 * @property string $centerid
 * @property string $warehouseid
 * @property string $masterid
 * @property string $createuser
 * @property string $createdate
 * @property string|null $closeuser
 * @property string|null $closedate
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string|null $statusid
 * @property string|null $schemaid
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence forMaster($masterid)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereClosedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereCloseuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereCreatedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereMasterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageMasterEvidence whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageMasterEvidence extends Model
{
    public $timestamps = false;

    protected $table = 'storagemasterevidence';

    protected $fillable = [
        'centerid', 'warehouseid', 'masterid', 'createuser', 'createdate',
        'closeuser', 'closedate', 'statusid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageMasterEvidence $model) {
                $model->createuser = Auth::user()->username;
                $model->createdate = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageMasterEvidence::whereIn('storagemasterevidence.warehouseid', $warehouses)
            ->whereIn('storagemasterevidence.centerid', $centers);
    }

    public function scopeForMaster($query, $masterid)
    {
        if (trim($masterid) != '') {
            $query->where('masterid', $masterid);
        }
    }
}
