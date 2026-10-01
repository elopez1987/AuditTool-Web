<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CheckListTransport extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

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

    public static function controlAccessUser()
    {
        $username = Auth::user()->username;

        return CheckListTransport::query()
            ->whereIn(
                'storagechecklisttransports.centerid',
                SecurityAccessWarehouse::query()
                    ->where('username', $username)
                    ->select('centerid'),
            );
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function checklistdetail(): BelongsTo
    {
        return $this->belongsTo(StorageCheckListDetail::class, 'checklitsdetailid', 'id');
    }
}
