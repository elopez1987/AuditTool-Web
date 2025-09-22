<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;

class ReceiptAsnCheckList extends Model
{
    public $timestamps = false;
    protected $table = 'receiptasnchecklist';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'receiptid',
        'checklistid',
        'checklistdetailid',
        'typeid',
        'createuser',
        'created_at',
    ];
}
