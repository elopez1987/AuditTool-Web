<?php

namespace App\Models\Maintenance;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorageCheckListActiveTruck extends Model
{
    public $timestamps = false;
    protected $table = 'storagechecklistactivetruck';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid', 'serialnumber', 'checklisttruckid', 'checklistid', 'responsecheck',
        'checklistdetailid', 'evidencepath', 'evidencekey', 'username',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageCheckListActiveTruck $model) {
                $model->username = Auth::user()->username;
                $model->created_at = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageCheckListActiveTruck::whereIn('storagechecklistactivetruck.centerid', $centers);
    }

    public function checklistdetail()
    {
        return $this->belongsTo(StorageCheckListDetailLiftTruck::class, 'checklistdetailid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

}
