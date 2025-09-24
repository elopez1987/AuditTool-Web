<?php

namespace App\Models\Receipt;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $recordid
 * @property string $centerid
 * @property int $receiptid
 * @property string|null $typeid
 * @property int|null $checklistid
 * @property int $checklistdetailid
 * @property string $createuser
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList whereChecklistdetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList whereChecklistid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList whereReceiptid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptAsnCheckList whereTypeid($value)
 * @mixin \Eloquent
 */
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
