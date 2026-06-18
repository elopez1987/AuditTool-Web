<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $folionumber
 * @property string $seal01
 * @property string|null $seal02
 * @property string|null $seal03
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property int $createuser
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereSeal01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereSeal02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereSeal03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitSeal whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ReceiptUnitSeal extends Model
{
    use LogsActivity;

    public $timestamps = true;

    protected $table = 'receiptunitseals';

    protected $fillable = [
        'centerid', 'warehouseid', 'folionumber', 'seal01', 'seal02', 'seal03', 'value01', 'value02', 'value3', 'createuser',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ReceiptUnitSeal::whereIn('receiptunitseals.warehouseid', $warehouses)
            ->whereIn('receiptunitseals.centerid', $centers);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

    public static function createNewUnitSeal(ReceiptUnit $receiptunit, $type)
    {
        ReceiptUnitSeal::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'seal01' => $receiptunit->containerseal,
            'value01' => $type,
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptUnitSeal $model) {
                $model->createuser = Auth::user()->id;
            });
        }
    }
}
