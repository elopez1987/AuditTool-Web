<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InventoryBox extends Model
{

    protected $table = 'inventoryboxstock';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'statustypeid',
        'description',
        'createdate',
        'createuser',
        'quantitybox',
        'quantityboxtrue',
        'statusid',
    ];

    protected $attributes = [
        'schematypeid' => 'INC',
        'schemaid' => 'IBX',
        'statusid' => 'PN'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (InventoryBox $model) {
                $model->createuser = Auth::id();
                $model->createdate = Carbon::now();
                $model->quantityboxtrue = '0';
            });
        }
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function type()
    {
        return $this->belongsTo(SystemStatus::class, 'statustypeid', 'statusid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }


    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return InventoryBox::whereIn('inventoryboxstock.centerid', $centers);
    }


    static function createNewInventory($request, $total)
    {
        return InventoryBox::create([
            'centerid' => $request['centerid'],
            'statustypeid' => $request['statustypeid'],
            'description' => $request['description'],
            'quantitybox' => $total,
        ]);
    }

    public function scopeForStatus($query, $statusid)
    {
        if (trim($statusid) != '') {
            $query->where('inventoryboxstock.statusid', $statusid);
        } else {
            $query->whereNotIn('inventoryboxstock.statusid', ['TO']);
        }
    }

    public function scopeForFolio($query, $folio)
    {
        if (trim($folio) != '') {
            $query->where('inventoryboxstock.id', $folio);
        }
    }

}
