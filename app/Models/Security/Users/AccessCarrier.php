<?php

namespace App\Models\Security\Users;

use Illuminate\Database\Eloquent\Model;

class AccessCarrier extends Model
{
    protected $table = 'securityaccesscarriers';
    public $timestamps = false;
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid', 'username', 'carrierid'
    ];
}
