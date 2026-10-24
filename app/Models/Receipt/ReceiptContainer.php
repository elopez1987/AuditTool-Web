<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptContainer extends Model
{
    protected $table = 'receiptcontainers';

    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid', 'warehouseid', 'receiptid', 'partnumberid', 'quantity', 'lpnid',
        'batch', 'productiondate', 'codeplant', 'eanbox', 'eanpz', 'photo',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return ReceiptContainer::whereIn('receiptcontainers.centerid', $centers)
            ->whereIn('receiptcontainers.warehouseid', $warehouses);
    }

    public function partnumber(): BelongsTo
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public static function getContainersForReceipt($receiptid): Collection
    {
        return ReceiptContainer::with('partnumber')
            ->where('receiptid', $receiptid)->get();
    }

    protected static function boot(): void
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptContainer $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }
}
