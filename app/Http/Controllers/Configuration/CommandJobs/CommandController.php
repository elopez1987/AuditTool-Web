<?php

namespace App\Http\Controllers\Configuration\CommandJobs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{

    public function commandmenu()
    {
        return view('Configuration.Commands.index');
    }

    public function clearcachesystem()
    {
        Artisan::call("config:clear");
        Artisan::call("config:cache");
        Artisan::call("cache:clear");
        Artisan::call("optimize:clear");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function senddatatorcontrol()
    {
        Artisan::call("rc:SendPlanForShipmentCommand");
        Artisan::call("rc:SendEventForShipmentCommand");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function senddatatoizel()
    {
        Artisan::call("command:SendDataToWmsIzel");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function cleartablelogwebservice()
    {
        Artisan::call("command:deletehistlogwebserviceizel");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function newincidencejourneys()
    {
        Artisan::call("command:EnviaMensajeDeNuevaIncidenciaRegistrada");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function sendmessagenotarriveunittoshipment()
    {
        Artisan::call("command:UnidadesSinLLegarEmbarques");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function getevidencesfromrcontrol()
    {
        Artisan::call("rc:getevidencefromrcontrol");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function sendMessageExitUnitTime()
    {
        Artisan::call("command:TiempoExcedidoParaSalirDePatio");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function getETAForShipment()
    {
        Artisan::call("rc:ObtenerETAdeRecursoConfiable");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function deletelogwhatsapp()
    {
        Artisan::call("whatsapp:deletelog");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function deleteusers()
    {
        Artisan::call("system:deleteuser");
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function clearReceiptAsn()
    {
        Artisan::call('clear:receiptasn');
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function sendmessagefornewarrivereceipt()
    {
        Artisan::call('app:send-message-for-receipt-entry');
        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

}
