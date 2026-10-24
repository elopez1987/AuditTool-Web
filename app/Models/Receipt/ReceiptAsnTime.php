<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptAsnTime extends Model
{
    public $timestamps = false;

    protected $table = 'receiptasntimes';

    protected $fillable = [
        'receiptid',
        'send_to_billing',
        'start_billing',
        'finish_billing',
        'arriveddate',
        'scanstartdate',
        'scanfinishdate',
        'unloadstartdate',
        'unloadfinishdate',
        'verifystartdate',
        'verifyfinishdate',
    ];

    protected $casts = [
        'send_to_billing' => 'datetime:Y-m-d H:i:s',
        'start_billing' => 'datetime:Y-m-d H:i:s',
        'finish_billing' => 'datetime:Y-m-d H:i:s',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(ReceiptAsn::class, 'receiptid');
    }
}
