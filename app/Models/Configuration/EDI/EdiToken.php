<?php

namespace App\Models\Configuration\EDI;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EdiToken extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

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
