<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderdeliveryTrace extends Model
{
    protected $table = 'orderdeliverytrace';

    protected $fillable = [
        'orderid',
        'eventid',
        'statusidold',
        'statusidnew',
        'userid',
    ];

    protected $attributes = [
        'schemaid' => 'EEM',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:s',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'eventid', 'statusid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function statusold(): HasOne
    {
        return $this->hasOne(SystemStatus::class, 'statusid', 'statusidold');
    }

    public function statusnew(): HasOne
    {
        return $this->hasOne(SystemStatus::class, 'statusid', 'statusidnew');
    }

    public static function saveEventShipment($order, $eventid, $statusid)
    {
        static::create([
            'orderid' => $order->id,
            'eventid' => $eventid,
            'statusidold' => $statusid,
            'statusidnew' => $order->statusid,
            'userid' => Auth::id(),
        ]);
    }
}
