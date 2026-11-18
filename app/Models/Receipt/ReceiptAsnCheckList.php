<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;

class ReceiptAsnCheckList extends Model
{
    public $timestamps = false;

    protected $table = 'receiptasnchecklist';

    protected $primaryKey = 'id';

    protected $fillable = [
        'receiptid',
        'typeid',
        'checklistid',
        'checklistdetailid',
        'createuser',
        'created_at',
    ];
}
