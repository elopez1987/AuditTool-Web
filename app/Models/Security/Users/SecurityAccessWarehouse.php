<?php

namespace App\Models\Security\Users;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class SecurityAccessWarehouse extends Model
{
    use LogsActivity;

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()->logFillable();
	}


	protected $table = 'securityaccesswarehouses';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'username',
        'defaultlevel',
    ];


//	Relaciones con modelos
	public function center()
	{
		return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
	}

	public function warehouse()
	{
		return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
	}


//	Scopes para filtros de acceso a usuarios

	/**
	 * Igual que llamar a Centers() y Warehouses(), pero en una sola consulta.
	 * Úsalo en lugares donde ambos arreglos se necesiten juntos (p.ej.
	 * controlAccessUser) para evitar un round-trip extra a la base de datos.
	 *
	 * @return array{centers: array<int, string>, warehouses: array<int, string>}
	 */
	public static function AccessScope(): array
	{
		$rows = SecurityAccessWarehouse::where('username', Auth::user()->username)
			->select('centerid', 'warehouseid')
			->get();

		return [
			'centers' => $rows->pluck('centerid')->all(),
			'warehouses' => $rows->pluck('warehouseid')->all(),
		];
	}

	
    public static function Centers()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)
            ->select('centerid')
            ->get()
            ->toArray();
    }

    public static function Warehouses()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)
            ->select('warehouseid')
            ->get()
            ->toArray();
    }

    public static function getCenterForUser()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)
			->value('centerid');
    }


}
