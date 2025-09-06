<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryCountStock extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'inventorycountstock';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'countid',
        'folionumber',
        'barcode',
        'boxnumber',
        'zoneid',
        'locationid',
        'newzoneid',
        'newlocationid',
        'countprogramdate',
        'statusid',
        'userid',
        'created_at',
        'updated_at',
    ];


    protected $attributes = [
        'schemaid' => 'RUS',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return InventoryCountStock::whereIn('inventorycountstock.centerid', $centers);
    }

    public function folio()
    {
        return $this->belongsTo(ReceiptUnit::class, 'folionumber', 'id');
    }

    public function inventory()
    {
        return $this->belongsTo(InventoryBox::class, 'countid', 'id');
    }

    static function createDetailCount($inventory, $unit)
    {
        InventoryCountStock::create([
            'centerid' => $inventory->centerid,
            'countid' => $inventory->id,
            'folionumber' => $unit->id,
            'barcode' => $unit->barcode,
            'boxnumber' => $unit->boxnumber,
            'zoneid' => $unit->zoneid,
            'locationid' => $unit->locationid,
            'statusid' => $unit->statusid,
            'countprogramdate' => Carbon::now()->toDateString(),
        ]);
    }


    static function searchForFolioNumber($id, $inventory)
    {
        return InventoryCountStock::where('folionumber', '=', $id)
            ->where('countid', $inventory->id)->first();
    }

    static function updateInventory($inventory,$request)
    {
        $inventory->newzoneid = $request['zoneid'];
        $inventory->newlocationid = $request['locationid'];
        $inventory->isupdate = 'Y';
        $inventory->userid = Auth::user()->id;
        $inventory->save();
    }

}
