<?php

namespace App\Models\Configuration\Okta;

use Illuminate\Database\Eloquent\Model;

class EdiWsToken extends Model
{
    public $timestamps = false;
    protected $table = 'ediwstokens';

    protected $fillable = [
        'baseuri',
        'client_id',
        'client_secret',
        'audience',
        'timeout',
        'tokentype'
    ];
}
