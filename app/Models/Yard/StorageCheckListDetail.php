<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property int $checklistid
 * @property string $description
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read \App\Models\Yard\StorageCheckList $checklist
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read SystemStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereChecklistid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageCheckListDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StorageCheckListDetail extends Model
{
    use Notifiable;
    use LogsActivity;

    protected $table = 'storagechecklistdetails';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'checklistid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'auxiliary06',
        'auxiliary07',
        'auxiliary08',
        'auxiliary09',
        'auxiliary10',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageCheckListDetail::whereIn('storagechecklistdetails.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function checklist()
    {
        return $this->belongsTo(StorageCheckList::class, 'checklistid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('name', 'like', "%{$name}%");
        }
    }
}
