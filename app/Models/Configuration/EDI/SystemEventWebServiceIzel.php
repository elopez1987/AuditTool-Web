<?php

namespace App\Models\Configuration\EDI;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SystemEventWebServiceIzel extends Model
{
    protected $table = "systemeventwebserviceizel";

    protected $fillable = [
        'centerid',
        'warehouseid',
        'folionumber',
        'boxnumber',
        'servicename',
        'processdate',
        'code',
        'message',
        'messagejson',
        'messagejsonresponse',
        'value01',
        'value02',
        'value03',
        'value04',
        'value05',
        'type',
    ];



    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid')
            ->select('name', 'centerid','description');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid')
            ->select('name', 'warehouseid');
    }

    protected $casts = [
        'messagejson' => 'json',
        'messagejsonresponse' => 'json',
    ];




    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return SystemEventWebServiceIzel::whereIn('systemeventwebserviceizel.centerid', $centers)
            ->whereIn('systemeventwebserviceizel.warehouseid', $warehouses);
    }

    static function createRegisterLog($receiptunit, $statusService, $parameter)
    {
        if (in_array('SDC-07', (array)$parameter)) {
//            Version 4
            $servicename = $statusService->Service_Name;
            $processdate = $statusService->ProcessDate;
            $code = $statusService->Code;
            $message = $statusService->Message;
        } else {
//            Version 3
            $servicename = $statusService->{'Service_Name'};
            $processdate = $statusService->{'ProcessDate'};
            $code = $statusService->{'Code'};
            $message = $statusService->{'Message'};
        }

        SystemEventWebServiceIzel::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'boxnumber' => $receiptunit->boxnumber,
            'servicename' => $servicename,
            'processdate' => $processdate,
            'code' => $code,
            'type' => 'IZEL',
            'message' => $message,
        ]);
    }

    static function createRegisterLogError($receiptunit, $statusService, $parameter)
    {
        if (in_array('SDC-07', (array)$parameter)) {
//            Version 4
            $servicename = '';
            $processdate = Carbon::now();
            $code = $statusService->Code;
            $message = $statusService->Description;
        } else {
//            Version 3
            $servicename = $statusService->{'Service_Name'};
            $processdate = $statusService->{'ProcessDate'};
            $code = $statusService->{'Code'};
            $message = $statusService->{'Message'};
        }

        SystemEventWebServiceIzel::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'boxnumber' => $receiptunit->boxnumber,
            'servicename' => $servicename,
            'processdate' => $processdate,
            'code' => $code,
            'type' => 'IZEL',
            'message' => $message,
        ]);
    }


    public function scopeFolioNumber($query, $folionumber)
    {
        if (trim($folionumber) != "") {
            $query->where('systemeventwebserviceizel.folionumber', 'like', "%$folionumber%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if ($boxnumber != "") {
            $query->where('systemeventwebserviceizel.boxnumber', 'like', "%$boxnumber%");
        }
    }


}
