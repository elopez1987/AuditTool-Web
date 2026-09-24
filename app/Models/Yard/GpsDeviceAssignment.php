<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GpsDeviceAssignment extends Model
{
    protected $fillable = [
        'gps_device_id',
        'receiptunit_id',
        'assigned_at',
        'unassigned_at',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(GpsDevice::class, 'gps_device_id', 'id');
    }

    public function receiptunit(): BelongsTo
    {
        return $this->belongsTo(ReceiptUnit::class, 'receiptunit_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'assigned_at' => 'timestamp',
            'unassigned_at' => 'timestamp',
        ];
    }
}
