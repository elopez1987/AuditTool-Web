<?php

namespace App\Models\Receipt;

use App\Models\Yard\StorageLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptAsnData extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'receiptasndata';

    public $timestamps = false;

    protected $fillable = [
        'receiptid', 'temperature',
        'locationid',
        'user_verification',
        'user_receiver',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsn::class, 'receiptid');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }
}
