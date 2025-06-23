<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class CheckListTransport extends Model
{
    use Notifiable;
    protected $table = 'storagechecklisttransports';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'checklistid',
        'receiptunitid',
        'checklitsdetailid',
        'verified',
        'type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return CheckListTransport::whereIn('storagechecklisttransports.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function checklistdetail()
    {
        return $this->belongsTo(StorageCheckListDetail::class, 'checklitsdetailid','id');
    }


}
