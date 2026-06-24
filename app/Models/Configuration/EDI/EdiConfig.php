<?php

namespace App\Models\Configuration\EDI;

use Illuminate\Database\Eloquent\Model;

class EdiConfig extends Model
{
    protected $table = 'ediconfigs';

    public $timestamps = false;

    protected $fillable = [
        'edi',
        'type',
        'standard',
        'description',
        'sendername',
        'centersender',
        'warehousesender',
        'centerrecipient',
        'warehouserecipient',
        'url',
        'userid',
        'password',
        'version',
        'enviroment',
        'systemclient',
        'client_id',
        'client_secret',
        'api_key',
        'instance_name',
    ];
}
