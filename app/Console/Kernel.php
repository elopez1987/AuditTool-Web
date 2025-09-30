<?php

namespace App\Console;

use App\Console\Commands\Bridgestone\SendMailForDifferenceWeightCommand;
use App\Console\Commands\Bridgestone\SendMessageDifferenceWeightCommand;
use App\Console\Commands\General\ClearReceiptAsnCommand;
use App\Console\Commands\General\CloseValidateQRReceipt;
use App\Console\Commands\General\DeleteHistLogWebServiceIzel;
use App\Console\Commands\General\DeleteLogWhatsApp;
use App\Console\Commands\General\MensajeDeCapacidadDePatio;
use App\Console\Commands\General\SendEmailForArriveUnitToReceipt;
use App\Console\Commands\General\SendMessageForReceiptEntry;
use App\Console\Commands\HENKELTOL01\ClearLocationCommand;
use App\Console\Commands\HENKELTOL01\EnviaMensajeDeNuevaIncidenciaRegistrada;
use App\Console\Commands\HENKELTOL01\TiempoExcedidoParaSalirDePatio;
use App\Console\Commands\HENKELTOL01\UnidadesSinLLegarEmbarques;
use App\Console\Commands\Izel\SendDataToWmsIzel;
use App\Console\Commands\RecursoConfiable\ONEROOF\GetEvidenceFromRControl;
use App\Console\Commands\RecursoConfiable\ONEROOF\ObtenerETAdeRecursoConfiable;
use App\Console\Commands\RecursoConfiable\ONEROOF\SendEventForShipmentCommand;
use App\Console\Commands\RecursoConfiable\ONEROOF\SendPlanForShipmentCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        DeleteHistLogWebServiceIzel::class,
        CloseValidateQRReceipt::class,
        DeleteLogWhatsApp::class,
        ClearReceiptAsnCommand::class,
        SendMessageForReceiptEntry::class,
        SendEmailForArriveUnitToReceipt::class,

        SendDataToWmsIzel::class,

        TiempoExcedidoParaSalirDePatio::class,
        ObtenerETAdeRecursoConfiable::class,
        UnidadesSinLLegarEmbarques::class,
        EnviaMensajeDeNuevaIncidenciaRegistrada::class,
        MensajeDeCapacidadDePatio::class,

        SendPlanForShipmentCommand::class,
        SendEventForShipmentCommand::class,

        GetEvidenceFromRControl::class,
        ClearLocationCommand::class,

        SendMessageDifferenceWeightCommand::class,
        SendMailForDifferenceWeightCommand::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //      Comandos generales
        $schedule->command('command:deletehistlogwebserviceizel')->weekly();
        $schedule->command('command:CloseValidateQRReceipt')->everyTenMinutes();
        $schedule->command('whatsapp:deletelog')->daily();
        $schedule->command('clear:receiptasn')->dailyAt('01:00');
        $schedule->command('app:send-message-for-receipt-entry')->everyFiveMinutes();
        $schedule->command('app:send-email-for-arrive-unit-to-receipt')->everyFiveMinutes();

        //        Comandos para la cuenta de Bridgestone
        //        Envio del correo de discrepancia en peso de salida de la unidad
        $schedule->command('send:mail-for-difference-weight')->everyFiveMinutes();
        //        Envío de mensaje de discrepancia en peso de salida de la unidad
        $schedule->command('send:message-difference-weight')->everyFiveMinutes();

        //      Comandos para Kellogg Toluca
        $schedule->command('command:SendDataToWmsIzel')->everyTenMinutes();

        //      Comandos para Henkel One Roof HENKELTOL01
        $schedule->command('command:TiempoExcedidoParaSalirDePatio')->everyTenMinutes();
        //        Envia mensajes de aunidades sin arrivar para embarques
        $schedule->command('command:UnidadesSinLLegarEmbarques')->everyThirtyMinutes();
        //        Envío de mensajes de nuevas incidencias en embarques
        $schedule->command('command:EnviaMensajeDeNuevaIncidenciaRegistrada')->everyFiveMinutes();
        //        Envia mensaje de capacidad de patio superada al parametro maximo
        $schedule->command('whatsapp:patiolleno')->everyTwoHours();
        //        Envia el plan de carga a recurso confiable
        $schedule->command('rc:SendPlanForShipmentCommand')->everyTwoHours();
        //        Enviar los eventos logisticos de la unidad a recurso confiable
        $schedule->command('rc:SendEventForShipmentCommand')->everyTenMinutes();
        //        Obtiene la ETA del embarque conectado a RC.
        $schedule->command('rc:ObtenerETAdeRecursoConfiable')->everyThirtyMinutes();
        //        Recolectar evidencia de POD de recurso confiable
        $schedule->command('rc:getevidencefromrcontrol')->dailyAt('10:00');
        //        Limpiar ubicaciones de cortinas y stages
        $schedule->command('system:clearlocation')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
