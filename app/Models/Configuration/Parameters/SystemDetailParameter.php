<?php

namespace App\Models\Configuration\Parameters;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SystemDetailParameter extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'systemdetailparameter';
    public $timestamps = false;

    protected $fillable = [
        'parameterid',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'value06',
        'value07',
        'value08',
        'value09',
        'value10',
        'value11',
        'value12',
        'value13',
        'value14',
        'value15',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return SystemDetailParameter::whereIn('systemdetailparameter.value02', $warehouses)
            ->whereIn('systemdetailparameter.value01', $centers);
    }



    static function getDetailParameter($parameterid, $centerid)
    {
        return SystemDetailParameter::where('parameterid', '=', $parameterid)
            ->where('value01', '=', $centerid)
            ->select(['value01', 'value02', 'value03'])->first();
    }

    static function getDateilParameterForCenterAndWarehouse($parameterid, $model)
    {
        return SystemDetailParameter::where('parameterid',
            '=', $parameterid)
            ->where('value01', '=', $model->centerid)
            ->where('value02', '=', $model->warehouseid)
            ->pluck('value04')->toArray();
    }




}
