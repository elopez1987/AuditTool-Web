<?php

namespace App\Models\SafetyTours;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;

class StorageSafetyTourDetail extends Model
{
    protected $table = 'storagesafetytourdetail';

    protected $fillable = [
        'centerid',
        'tourid',
        'tagid',
        'sequence',
        'latitude',
        'longitude',
        'schematype',
        'statustype',
        'comment',
        'evidencefile',
        'userid',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'RSE-01',
        'schemaid' => 'RSE',
        'schematype' => 'CTR',  // Esquema de categorias de registro
    ];

//    protected static function boot()
//    {
//        parent::boot();
//        if (!app()->runningInConsole()) {
//            self::creating(function (StorageSafetyTourDetail $model) {
//                $model->userid = Auth::id();
//            });
//        }
//    }


    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageSafetyTourDetail::whereIn('storagesafetytourdetail.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function tag()
    {
        return $this->belongsTo(StorageTags::class, 'tagid', 'id')->withTrashed();
    }

    public function tour()
    {
        return $this->belongsTo(StorageSafetyTours::class, 'tourid', 'id');
    }

    public function categorytype()
    {
        return $this->belongsTo(SystemStatus::class, 'statustype', 'statusid');
    }




}
