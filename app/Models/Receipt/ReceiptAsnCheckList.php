<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;

class ReceiptAsnCheckList extends Model
{

    protected $table = 'receiptasnchecklist';
    protected $primaryKey = 'recordid';
    public $timestamps = false;


    protected $fillable = [
        'centerid',
        'receiptid',
        'checklistid',
        'checklistdetailid',
        'typeid',
        'createuser',
        'created_at'
    ];




}
