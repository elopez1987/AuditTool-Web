<?php

namespace App\Models\Shipments;

use Auth;
use Illuminate\Database\Eloquent\Model;

class ScheduleInverseLog extends Model
{
    protected $table = 'scheduleinverselog';

    protected $fillable = [
        'orderid', 'username', 'description',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'AG',
        'schemaid' => 'LIN',
    ];

    static function createNewLog($shipment,$description)
    {
        ScheduleInverseLog::create([
            'orderid' => $shipment->id,
            'username' => Auth::user()->username,
            'description' => $description
        ]);
    }


}
