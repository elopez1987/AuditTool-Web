<?php

namespace App\Models\Configuration\WhatsApp;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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

	public function certer()
	{
		return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
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
