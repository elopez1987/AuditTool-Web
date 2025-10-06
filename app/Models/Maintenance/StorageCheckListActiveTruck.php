<?php

namespace App\Models\Maintenance;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $serialnumber
 * @property int $checklisttruckid
 * @property int $checklistid
 * @property int $checklistdetailid
 * @property string $responsecheck
 * @property string|null $evidencepath
 * @property string $evidencekey
 * @property string $username
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Maintenance\StorageCheckListDetailLiftTruck|null $checklistdetail
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereChecklistdetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereChecklistid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereChecklisttruckid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereEvidencekey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereEvidencepath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereResponsecheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereSerialnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListActiveTruck whereValue05($value)
 *
 * @mixin \Eloquent
 */
class StorageCheckListActiveTruck extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'storagechecklistactivetruck';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid', 'serialnumber', 'checklisttruckid', 'checklistid', 'responsecheck',
        'checklistdetailid', 'evidencepath', 'evidencekey', 'username',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageCheckListActiveTruck::whereIn('storagechecklistactivetruck.centerid', $centers);
    }

    public function checklistdetail()
    {
        return $this->belongsTo(StorageCheckListDetailLiftTruck::class, 'checklistdetailid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageCheckListActiveTruck $model) {
                $model->username = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }
}
