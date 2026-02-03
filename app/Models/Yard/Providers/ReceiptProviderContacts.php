<?php

namespace App\Models\Yard\Providers;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Receipt\ReceiptProvider;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ReceiptProviderContacts extends Model
{
    protected $table = 'receiptprovidercontacts';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'providerid',
        'name',
        'email',
        'phone',
        'value01',
        'value02',
        'value03',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptProviderContacts $model) {
                $model->created_by = Auth::user()->id;
            });
        }
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ReceiptProvider::class, 'providerid');
    }

    public function createuser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
