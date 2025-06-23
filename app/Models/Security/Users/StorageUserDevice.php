<?php

namespace App\Models\Security\Users;

use Illuminate\Database\Eloquent\Model;

class StorageUserDevice extends Model
{
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
