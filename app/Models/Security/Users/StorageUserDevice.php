<?php

namespace App\Models\Security\Users;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageUserDevice extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'securitydeviceforuser';

    protected $fillable = [
        'id',
        'username',
        'serialnumber',
        'created_at'
    ];


    public function scopeForSerialNumber($query, $serialnumber)
    {
        if (trim($serialnumber) != '') {
            $query->where('securitydeviceforuser.serialnumber', $serialnumber);
        }
    }



}
