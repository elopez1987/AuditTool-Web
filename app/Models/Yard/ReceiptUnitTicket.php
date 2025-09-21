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
        if (trim($ticket) !== '') {
            $query->where('storagereceiptunitticket.ticket', 'like', "%{$ticket}%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) !== '') {
            $query->where('storagereceiptunitticket.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeFolioNumber($query, $folionumber)
    {
        if (trim($folionumber) !== '') {
            $query->where('storagereceiptunitticket.folionumber', 'like', "%{$folionumber}%");
        }
    }

    public function scopeCarrier()
    {
        if (trim($carrier) !== '') {
            $query->where('storagereceiptunitticket.carrierid', '=', $carrier);
        }
    }

    public function scopeWarehose()
    {
        if (trim($warehouse) !== '') {
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
