<?php

namespace App\Models\Shipments;

use Illuminate\Database\Eloquent\Model;

class ShipmentStage extends Model
{

    protected $table = 'shipmentstages';
    public $timestamps = false;

    protected $fillable = [
        'orderid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
    ];


}
