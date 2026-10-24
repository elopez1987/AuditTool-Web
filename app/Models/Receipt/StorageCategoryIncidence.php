<?php

namespace App\Models\Receipt;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageCategoryIncidence extends Model
{
    public $timestamps = false;

    protected $table = 'storagecategoryincidences';

    protected $primaryKey = 'categoryincid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'description',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return StorageCategoryIncidence::whereIn('storagecategoryincidences.centerid', $centers)
            ->whereIn('storagecategoryincidences.warehouseid', $warehouses);
    }

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

    public function scopeForDescription($query, $description)
    {
        if (trim($description) != '') {
            $query->where('storagecategoryincidences.description', 'like', "%{$description}%");
        }
    }
}
