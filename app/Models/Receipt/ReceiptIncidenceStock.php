<?php

namespace App\Models\Receipt;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReceiptIncidenceStock extends Model
{
    public $timestamps = false;
    protected $table = 'receiptincidencestock';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'receiptid',
        'partnumberid',
        'quantity',
        'categoryid',
        'categorydetailid',
        'createuser',
        'created_at',
        'unitmeasureid',
    ];

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function stock()
    {
        return $this->belongsTo(ReceiptStock::class, 'receiptid', 'receiptid');
    }

    public function categoryincidence()
    {
        return $this->belongsTo(StorageCategoryIncidence::class, 'categoryid', 'categoryincid');
    }

    public function subcategoryincidence()
    {
        return $this->belongsTo(StorageCategoryIncidenceDetail::class, 'categorydetailid', 'categoryincdetailid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptIncidenceStock $model) {
                $model->createuser = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }
}
