<?php

namespace App\Models\Configuration\EDI;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string|null $folionumber
 * @property string|null $boxnumber
 * @property string|null $servicename
 * @property string|null $processdate
 * @property string|null $code
 * @property string|null $type
 * @property string|null $message
 * @property array<array-key, mixed>|null $messagejson
 * @property array<array-key, mixed>|null $messagejsonresponse
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemWarehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel boxNumber($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel folioNumber($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereMessagejson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereMessagejsonresponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereProcessdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereServicename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemEventWebServiceIzel whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class SystemEventWebServiceIzel extends Model
{
    use LogsActivity;

    protected $table = 'systemeventwebserviceizel';

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

    protected $casts = [
        'messagejson' => 'json',
        'messagejsonresponse' => 'json',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid')
            ->select('name', 'centerid', 'description');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid')
            ->select('name', 'warehouseid');
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return SystemEventWebServiceIzel::whereIn('systemeventwebserviceizel.centerid', $centers)
            ->whereIn('systemeventwebserviceizel.warehouseid', $warehouses);
    }

    public static function createRegisterLog($receiptunit, $statusService, $parameter)
    {
        if (in_array('SDC-07', (array) $parameter)) {
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

    public static function createRegisterLogError($receiptunit, $statusService, $parameter)
    {
        if (in_array('SDC-07', (array) $parameter)) {
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
        if (trim($folionumber) !== '') {
            $query->where('systemeventwebserviceizel.folionumber', 'like', "%{$folionumber}%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if ($boxnumber !== '') {
            $query->where('systemeventwebserviceizel.boxnumber', 'like', "%{$boxnumber}%");
        }
    }
}
