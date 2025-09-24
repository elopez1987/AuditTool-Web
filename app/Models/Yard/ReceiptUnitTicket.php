<?php

namespace App\Models\Yard;

use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $folionumber
 * @property string|null $ticket
 * @property string|null $boxnumber
 * @property float|null $weightin
 * @property float|null $weightinlb
 * @property float|null $weightout
 * @property float|null $weightoutlb
 * @property float|null $weightoutsap
 * @property string|null $weightdifferencelb
 * @property int $createuser
 * @property int|null $updateuser
 * @property int|null $responsibleid
 * @property int|null $areaid
 * @property int|null $contentid
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $createddate
 * @property string|null $updateddate
 * @property string|null $statusid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Yard\ReceiptUnit $folio
 * @property-read SystemStatus|null $status
 * @property-read User $usercreate
 * @property-read User|null $userupdate
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket boxNumber($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket carrier()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket folioNumber($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket ticket($ticket)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket warehose()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereAreaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereContentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereCreateddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereResponsibleid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereTicket($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereUpdateddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereUpdateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereWeightdifferencelb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereWeightin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereWeightinlb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereWeightout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereWeightoutlb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitTicket whereWeightoutsap($value)
 * @mixin \Eloquent
 */
class ReceiptUnitTicket extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'storagereceiptunitticket';

    protected $fillable = [
        'folionumber', 'ticket', 'weightin',
        'weightout', 'createuser', 'updateuser',
        'responsibleid', 'areaid', 'contentid',
        'value01', 'value02', 'value03', 'weightoutsap', 'updateddate',
        'weightoutlb', 'weightinlb', 'weightdifferencelb',
        'boxnumber', 'centerid', 'statusid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    //  -----------------------  Relaciones entre modelos  ---------------------------------------------
    public function usercreate()
    {
        return $this->belongsTo(User::class, 'createuser', 'id')->withTrashed();
    }

    public function userupdate()
    {
        return $this->belongsTo(User::class, 'updateuser', 'id')->withTrashed();
    }

    public function folio()
    {
        return $this->belongsTo(ReceiptUnit::class, 'folionumber', 'id');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    //  -----------------------  Scopes para filtros ---------------------------------------------

    public function scopeTicket($query, $ticket)
    {
        if (trim($ticket) != '') {
            $query->where('storagereceiptunitticket.ticket', 'like', "%{$ticket}%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('storagereceiptunitticket.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeFolioNumber($query, $folionumber)
    {
        if (trim($folionumber) != '') {
            $query->where('storagereceiptunitticket.folionumber', 'like', "%{$folionumber}%");
        }
    }

    public function scopeCarrier()
    {
        if (trim($carrier) != '') {
            $query->where('storagereceiptunitticket.carrierid', '=', $carrier);
        }
    }

    public function scopeWarehose()
    {
        if (trim($warehouse) != '') {
            $query->where('storagereceiptunitticket.warehouseid', '=', $warehouse);
        }
    }

    //  -----------------------  Funciones auxiliares para los modelos   ---------------------------------------------
    public static function createNewTicketUnit(Request $request, $folionumber)
    {
        ReceiptUnitTicket::create([
            'folionumber' => $folionumber->id,
            'ticket' => $request['tiket'],
            'weightin' => $request['weightin'],
            'responsibleid' => $request['responsibleid'],
            'areaid' => $request['areaid'],
            'contentid' => $request['contentid'],
            'boxnumber' => $request['boxnumber'],
        ]);
    }

    public static function closeTicket($folionumber, $request)
    {
        ReceiptUnitTicket::where('folionumber', $folionumber)
            ->where('ticket', $request['ticket'])
            ->update([
                'weightout' => $request['weightout'],
                'weightoutlb' => $request['weightoutlb'],
                'weightdifferencelb' => $request['weightdifferencelb'] ?? '',
                'weightoutsap' => $request['weightoutsap'],
            ]);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ReceiptUnitTicket $model) {
                $model->createuser = Auth::user()->id;
                $model->createddate = Carbon::now();
            });
            self::updating(function (ReceiptUnitTicket $model) {
                $model->updateuser = Auth::user()->id;
                $model->updateddate = Carbon::now();
            });
        }
    }
    //  -----------------------  Funciones auxiliares para los modelos   ---------------------------------------------
}
