<?php

namespace App\Models\Security\Users;

use App\Models\Shipments\OrderDeliverie;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SuperUser extends Model
{
    public $timestamps = false;
    protected $table = 'securitysuperusers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'userid',
        'created_at'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (SuperUser $model) {
                $model->created_at = Carbon::now();
            });
        }
    }



}
