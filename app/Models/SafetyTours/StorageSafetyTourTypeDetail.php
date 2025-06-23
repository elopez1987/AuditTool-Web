<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\StorageSafetyTourType;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StorageSafetyTourTypeDetail extends Model
{
    use SoftDeletes;

    protected $table = 'storagesafetytourtypedetails';

    protected $fillable = [
      'centerid', 'typeid', 'tagid', 'userid', 'value01', 'value02', 'value03',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageSafetyTourTypeDetail $model) {
                $model->userid = Auth::id();
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSafetyTourTypeDetail::whereIn('storagesafetytourtypedetails.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(StorageSafetyTourType::class, 'typeid');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(StorageTags::class, 'tagid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
