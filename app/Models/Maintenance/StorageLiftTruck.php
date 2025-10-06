<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $centerid
 * @property string $serialnumber
 * @property string $numbertruck
 * @property string $schematypeid
 * @property string $typeid
 * @property string $schemabrandid
 * @property string $brandid
 * @property string $schemamodelid
 * @property string $modelid
 * @property string $createuser
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read SystemStatus|null $brand
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $model
 * @property-read SystemStatus $status
 * @property-read SystemStatus|null $type
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck numberTruck($numbertruck)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck serialNumber($serialnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck status($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereBrandid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereModelid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereNumbertruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereSchemabrandid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereSchemamodelid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereSchematypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereSerialnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereTypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLiftTruck withoutTrashed()
 *
 * @mixin \Eloquent
 */
class StorageLiftTruck extends Model
{
    use SoftDeletes;

    protected $table = 'storagelifttrucks';

    protected $primaryKey = 'serialnumber';

    protected $keyType = 'string';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'numbertruck',
        'typeid',
        'brandid',
        'modelid',
        'axuiliary01',
        'axuiliary02',
        'axuiliary03',
        'axuiliary04',
        'axuiliary05',
        'statusid',
        'deleted_at',

    ];

    protected $hidden = [
        //        'created_at',
        'updated_at',
        'deleted_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'MT-A',
        'schemaid' => 'STM',

        'schematypeid' => 'TPM',
        'schemabrandid' => 'MCM',
        'schemamodelid' => 'MDM',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
        'updated_at' => 'datetime:d/m/Y H:s',
        'deleted_at' => 'datetime:d/m/Y H:s',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageLiftTruck::whereIn('storagelifttrucks.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function type()
    {
        return $this->belongsTo(SystemStatus::class, 'typeid', 'statusid');
    }

    public function model()
    {
        return $this->belongsTo(SystemStatus::class, 'modelid', 'statusid');
    }

    public function brand()
    {
        return $this->belongsTo(SystemStatus::class, 'brandid', 'statusid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public function scopeSerialNumber($q, $serialnumber)
    {
        if ($serialnumber !== '') {
            $q->where('serialnumber', $serialnumber);
        }
    }

    public function scopeNumberTruck($q, $numbertruck)
    {
        if ($numbertruck !== '') {
            $q->where('numbertruck', $numbertruck);
        }
    }

    public function scopeStatus($q, $statusid)
    {
        if ($statusid !== '') {
            $q->where('storagelifttrucks.statusid', $statusid);
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageLiftTruck $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }
}
