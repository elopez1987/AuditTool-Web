<?php

namespace App\Models\Configuration\WhatsApp;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageTaskWhatsapp extends Model
{
	use LogsActivity;

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()->logFillable();
	}

	public $timestamps = false;
	protected $table = 'storagetaskwhatsapp';

	protected $fillable = [
		'centerid', 'warehouseid', 'modelid',
		'modeltype', 'whatsappid', 'phone ', 'value01',
		'value02', 'value03', 'value04', 'value05', 'statusid'
	];


	protected $hidden = [
		'created_at',
		'updated_at',
		'schemaid'
	];

	protected $attributes = [
		'statusid' => 'E',
		'schemaid' => 'STS',
	];

	public function center(): BelongsTo
	{
		return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
	}

	public function warehouse(): BelongsTo
	{
		return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
	}

	public function status(): BelongsTo
	{
		return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
	}

	protected static function boot()
	{
		parent::boot();
		if (!app()->runningInConsole()) {
			self::creating(function (StorageTaskWhatsapp $model) {
				$model->created_at = Carbon::now();
			});
		}
	}

	public static function controlAccessUser()
	{
		$warehouses = SecurityAccessWarehouse::Warehouses();
		$centers = SecurityAccessWarehouse::Centers();
		return StorageTaskWhatsapp::whereIn('storagetaskwhatsapp.warehouseid', $warehouses)
			->whereIn('storagetaskwhatsapp.centerid', $centers);
	}

	public static function createNewTask($model, $key_id, $whatsapptype, $type)
	{
		StorageTaskWhatsapp::create([
			'centerid' => $model->centerid,
			'warehouseid' => $model->warehouseid,
			'modelid' => $key_id,
			'modeltype' => $model->getTable(),
			'whatsappid' => $whatsapptype,
			'value01' => $type
		]);
	}


}
