<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Illuminate\Database\Eloquent\Model;

class StorageShipmentsPods extends Model
{
    protected $table = 'storageshipmentspods';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'pathfile',
        'createuser',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'PED',
        'schemaid' => 'POD',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
