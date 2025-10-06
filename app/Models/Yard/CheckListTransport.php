<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property int $checklistid
 * @property int $receiptunitid
 * @property int $checklitsdetailid
 * @property string|null $verified
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read \App\Models\Yard\StorageCheckListDetail $checklistdetail
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereChecklistid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereChecklitsdetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereReceiptunitid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckListTransport whereVerified($value)
 *
 * @mixin \Eloquent
 */
class CheckListTransport extends Model
{
    use LogsActivity;
    use Notifiable;

    protected $table = 'storagechecklisttransports';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'checklistid',
        'receiptunitid',
        'checklitsdetailid',
        'verified',
        'type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return CheckListTransport::whereIn('storagechecklisttransports.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function checklistdetail()
    {
        return $this->belongsTo(StorageCheckListDetail::class, 'checklitsdetailid', 'id');
    }
}
