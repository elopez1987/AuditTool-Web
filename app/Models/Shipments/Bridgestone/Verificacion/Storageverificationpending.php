<?php

namespace App\Models\Shipments\Bridgestone\Verificacion;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Storageverificationpending extends Model
{
    public $timestamps = false;

    protected $table = 'storageverificationpending';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'countvigid',
        'countoperid',
        'countviguser',
        'countoperuser',
        'model_type',
        'schemaid',
        'statusid',
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public function usersupr(): BelongsTo
    {
        return $this->belongsTo(User::class, 'countoperuser', 'id');
    }
}
