<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $schematypeid
 * @property string $statustypeid
 * @property string $description
 * @property string $createdate
 * @property int|null $createuser
 * @property int|null $quantitybox
 * @property int|null $quantityboxtrue
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 * @property-read SystemStatus|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox forFolio($folio)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox forStatus($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereCreatedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereQuantitybox($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereQuantityboxtrue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereSchematypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryBox whereStatustypeid($value)
 *
 * @mixin \Eloquent
 */
class InventoryBox extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'inventoryboxstock';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'statustypeid',
        'description',
        'createdate',
        'createuser',
        'quantitybox',
        'quantityboxtrue',
        'statusid',
    ];

    protected $attributes = [
        'schematypeid' => 'INC',
        'schemaid' => 'IBX',
        'statusid' => 'PN',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function type()
    {
        return $this->belongsTo(SystemStatus::class, 'statustypeid', 'statusid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return InventoryBox::whereIn('inventoryboxstock.centerid', $centers);
    }

    public function scopeForStatus($query, $statusid)
    {
        if (trim($statusid) !== '') {
            $query->where('inventoryboxstock.statusid', $statusid);
        } else {
            $query->whereNotIn('inventoryboxstock.statusid', ['TO']);
        }
    }

    public function scopeForFolio($query, $folio)
    {
        if (trim($folio) !== '') {
            $query->where('inventoryboxstock.id', $folio);
        }
    }

    public static function createNewInventory($request, $total)
    {
        return InventoryBox::create([
            'centerid' => $request['centerid'],
            'statustypeid' => $request['statustypeid'],
            'description' => $request['description'],
            'quantitybox' => $total,
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (InventoryBox $model) {
                $model->createuser = Auth::id();
                $model->createdate = Carbon::now();
                $model->quantityboxtrue = '0';
            });
        }
    }
}
