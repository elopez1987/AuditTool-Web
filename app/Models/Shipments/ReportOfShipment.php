<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;

class ReportOfShipment extends Model
{
    protected $table = 'totalporturno';
    public $timestamps = false;

    protected $fillable = [
        'centerid',
        'turnname',
        'statusname',
        'totalshipments',
        'pallets',
        'cajas',
        'transporte'
    ];

}
