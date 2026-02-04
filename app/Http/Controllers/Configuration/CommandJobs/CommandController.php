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
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');

        return redirect()->back()->with(session()->flash('success', 'Proceso de limpieza de sistema terminado con éxito.'));
    }

    public function senddatatorcontrol()
    {
        Artisan::call('rc:SendPlanForShipmentCommand');
        Artisan::call('rc:SendEventForShipmentCommand');

        return redirect()->back()->with(session()->flash('success', 'Los datos fueron enviados con éxito a Recurso Confiable.'));
    }

    public function senddatatoizel()
    {
        Artisan::call('izel:enviar-datos-izel-wms');

        return redirect()->back()->with(session()->flash('success', 'Datos enviados con éxito a Izel.'));
    }

    public function cleartablelogwebservice()
    {
        Artisan::call('command:deletehistlogwebserviceizel');

        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con éxito'));
    }

    public function newincidencejourneys()
    {
        Artisan::call('command:EnviaMensajeDeNuevaIncidenciaRegistrada');

        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con exito'));
    }

    public function sendmessagenotarriveunittoshipment()
    {
        Artisan::call('command:UnidadesSinLLegarEmbarques');

        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con éxito'));
    }

    public function getevidencesfromrcontrol()
    {
        Artisan::call('rc:getevidencefromrcontrol');

        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con éxito'));
    }

    public function sendMessageExitUnitTime()
    {
        Artisan::call('command:TiempoExcedidoParaSalirDePatio');

        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con éxito'));
    }

    public function getETAForShipment()
    {
        Artisan::call('rc:ObtenerETAdeRecursoConfiable');

        return redirect()->back()->with(session()->flash('success', 'Proceso terminado con éxito'));
    }

    public function deletelogwhatsapp()
    {
        Artisan::call('whatsapp:deletelog');

        return redirect()->back()->with(session()->flash('success', 'Limpieza de Logs de WhatsApp ejecutado con éxito.'));
    }

    public function deleteusers()
    {
        Artisan::call('system:deleteuser');

        return redirect()->back()->with(session()->flash('success', 'Proceso de borrado de usuarios sin inicio de sesión terminado con éxito.'));
    }

    public function clearReceiptAsn()
    {
        Artisan::call('clear:receiptasn');

        return redirect()->back()->with(session()->flash('success', 'Limpieza de recibos sin llegar realizados con éxito.'));
    }

    public function sendmessagefornewarrivereceipt()
    {
        Artisan::call('app:send-message-for-receipt-entry');

        return redirect()->back()->with(session()->flash('success', 'Mensajes enviado a los supervisores notificando de la llegada de la unidad.'));
    }

    public function sendmailfornewarrivereceipt()
    {
        Artisan::call('app:send-email-for-arrive-unit-to-receipt');

        return redirect()->back()->with(session()->flash('success', 'Correos enviado a los supervisores notificando de la llegada de la unidad.'));
    }

    public function sendmaiilfordifferenceweight()
    {
        Artisan::call('send:mail-for-difference-weight');

        return redirect()->back()->with(session()->flash('success', 'Correo enviado a los supervisores notificando de la diferencia de peso.'));
    }

    public function sendmessagefordifferenceweight()
    {
        Artisan::call('send:message-difference-weight');

        return redirect()->back()->with(session()->flash('success', 'Mensajes enviado a los supervisores notificando de la diferencia de peso.'));
    }

    public function createuuidtouser()
    {
        Artisan::call('create:uuid-to-user');

        return redirect()->back()->with(session()->flash('success', 'Los usuarios ahora cuentan con un UUID.'));
    }
}
