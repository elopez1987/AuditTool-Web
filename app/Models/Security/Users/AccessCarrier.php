<?php

namespace App\Models\Security\Users;

use Illuminate\Database\Eloquent\Model;

class AccessCarrier extends Model
{
    public $timestamps = false;
    protected $table = 'securityaccesscarriers';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid', 'username', 'carrierid',
    ];
}
