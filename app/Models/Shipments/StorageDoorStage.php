<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $centerid
 * @property int $doorid
 * @property int $stageid
 * @property string $username
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage whereDoorid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage whereStageid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageDoorStage whereUsername($value)
 * @mixin \Eloquent
 */
class StorageDoorStage extends Model
{
    public $timestamps = false;
    protected $table = 'storagedoorstages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'doorid',
        'stageid',
        'username',
        'created_at',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageDoorStage::whereIn('storagedoorstages.centerid', $centers);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageDoorStage $model) {
                $model->username = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }
}
