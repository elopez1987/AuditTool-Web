<?php

namespace App\Models\Configuration\EDI;

use Illuminate\Database\Eloquent\Model;

class EdiToken extends Model
{
    public $timestamps = false;
    protected $table = 'ediwstokens';


    protected $fillable = [
        'baseuri',
        'client_id',
        'client_secret',
        'audience',
        'timeout',
        'tokentype',
        'servicetype',
    ];
}
