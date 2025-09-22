<?php

namespace App\Models\Configuration\EDI;

use Illuminate\Database\Eloquent\Model;

class EdiLog extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $table = 'logimports';
    protected $keyType = 'integer';

    protected $fillable = [
        'message',
        'created_at',
    ];
}
