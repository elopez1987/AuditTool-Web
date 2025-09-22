<?php

namespace App\Models\Configuration\Okta;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EdiWsToken extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'ediwstokens';

    protected $fillable = [
        'baseuri',
        'client_id',
        'client_secret',
        'audience',
        'timeout',
        'tokentype',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
