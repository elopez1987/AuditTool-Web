<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $slug
 * @property string $name
 * @property string|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus|null $status
 * @property-read SystemWarehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas status($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageAreas withoutTrashed()
 * @mixin \Eloquent
 */
class StorageAreas extends Model
{
	use SoftDeletes;
	use LogsActivity;

	protected $table = 'storageareas';

	protected $hidden = [
		'created_at',
		'updated_at',
		'deleted_at',
		'schemaid',
	];

	protected $attributes = [
		'statusid' => 'E',
		'schemaid' => 'STS',
	];

	protected $fillable = [
		'centerid',
		'warehouseid',
		'slug',
		'name',
		'configurationflag',
		'auxiliary01',
		'auxiliary02',
		'auxiliary03',
		'statusid',
	];

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()->logFillable();
	}

	public static function controlAccessUser()
	{
		$warehouses = SecurityAccessWarehouse::Warehouses();
		$centers = SecurityAccessWarehouse::Centers();
		return StorageAreas::whereIn('storageareas.warehouseid', $warehouses)
			->whereIn('storageareas.centerid', $centers);
	}

	public function center()
	{
		return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
	}

	public function warehouse()
	{
		return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
	}

	public function status()
	{
		return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
	}

	public function scopeName($query, $name)
	{
		if (trim($name) != '') {
			$query->where('storageareas.name', 'like', "%{$name}%");
		}
	}

	public function scopeStatus($query, $status)
	{
		if (trim($status) != '') {
			$query->where('storageareas.statusid', '=', $status);
		} else {
			$query->where('storageareas.statusid', '=', 'E');
		}
	}

	public function scopeWarehouse($query, $warehouseid)
	{
		if (trim($warehouseid) != '') {
			$query->where('storageareas.warehouseid', '=', $warehouseid);
		}
	}

	protected static function boot()
	{
		parent::boot();
		if (!app()->runningInConsole()) {
			self::creating(function (StorageAreas $model) {
				$model->slug = Uuid::uuid4();
			});
		}
	}
}
