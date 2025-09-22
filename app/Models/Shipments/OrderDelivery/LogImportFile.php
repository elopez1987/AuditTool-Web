<?php

namespace App\Models\Shipments\OrderDelivery;

use Illuminate\Database\Eloquent\Model;

class LogImportFile extends Model
{
    public $timestamps = false;
    protected $table = 'logimports';

    protected $fillable = [
        'message', 'created_at',
    ];
}
