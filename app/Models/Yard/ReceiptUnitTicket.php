<?php

namespace App\Models\Yard;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ReceiptUnitTicket extends Model
{
    public $timestamps = false;
    protected $table = 'storagereceiptunitticket';

    protected $fillable = [
        'folionumber', 'ticket', 'weightin',
        'weightout', 'createuser', 'updateuser',
        'responsibleid', 'areaid', 'contentid',
        'value01','value02','value03'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ReceiptUnitTicket $model) {
                $model->createuser = \Auth::user()->id;
                $model->createddate = Carbon::now();
            });
            self::updating(function (ReceiptUnitTicket $model) {
                $model->updateuser = \Auth::user()->id;
                $model->updateddate = Carbon::now();
            });
        }
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

//  -----------------------  Relaciones entre modelos  ---------------------------------------------


//  -----------------------  Funciones auxiliares para los modelos   ---------------------------------------------
    static function createNewTicketUnit(Request $request, $folionumber)
    {
        ReceiptUnitTicket::create([
            'folionumber' => $folionumber->id,
            'ticket' => $request['tiket'],
            'weightin' => $request['weightin'],
            'responsibleid' => $request['responsibleid'],
            'areaid' => $request['areaid'],
            'contentid' => $request['contentid'],
            'value01' => Carbon::now(),
        ]);
    }

    static function closeTicket($folionumber, $request)
    {
        ReceiptUnitTicket::where('folionumber', $folionumber)
            ->where('ticket', $request['ticket'])
            ->update([
                'weightout' => $request['weightout'],
                'updateuser' => \Auth::user()->id,
                'updateddate' => Carbon::now(),
                'value02' => Carbon::now(),
            ]);
    }
//  -----------------------  Funciones auxiliares para los modelos   ---------------------------------------------

}
