<?php

namespace App\Models\Yard\ValidateOperatorInRControl;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StorageValidateOperator extends Model
{
    public $timestamps = false;
    protected $table = 'storagevalidateoperators';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid', 'orderid', 'operatorname', 'statusoperator', 'created_at'
    ];

    protected $hidden = [
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageValidateOperator $model) {
                $model->userid = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageValidateOperator::whereIn('storagevalidateoperators.centerid', $centers);
    }



}
