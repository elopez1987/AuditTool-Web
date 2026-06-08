<?php

namespace App\Models\Shipments;

use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string|null $shipment
 * @property string $description
 * @property string $username
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereShipment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLogInverse whereValue05($value)
 *
 * @mixin \Eloquent
 */
class StorageLogInverse extends Model
{
    use LogsActivity;

    protected $table = 'storageloginverse';

    protected $fillable = [
        'username', 'description', 'shipment',
        'value01', 'value02', 'value03',
        'value04', 'value05',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function createNewLog($description, $shipment)
    {
        StorageLogInverse::create([
            'description' => $description,
            'shipment' => $shipment,
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageLogInverse $model) {
                $model->username = Auth::user()->username;
            });
        }
    }
}
