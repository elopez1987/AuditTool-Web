<?php

namespace App\Console;

use App\Console\Commands\General\ClearReceiptAsnCommand;
use App\Console\Commands\General\CloseValidateQRReceipt;
use App\Console\Commands\General\DeleteHistLogWebServiceIzel;
use App\Console\Commands\General\DeleteLogWhatsApp;
use App\Console\Commands\General\MensajeDeCapacidadDePatio;
use App\Console\Commands\General\TiempoExcedidoParaSalirDePatio;
use App\Console\Commands\General\UnidadesSinLLegarEmbarques;
use App\Console\Commands\HENKELTOL01\ClearLocationCommand;
use App\Console\Commands\HENKELTOL01\EnviaMensajeDeNuevaIncidenciaRegistrada;
use App\Console\Commands\Izel\EnviarDatosIzelWmsCommand;
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

        TiempoExcedidoParaSalirDePatio::class,
        ObtenerETAdeRecursoConfiable::class,
        UnidadesSinLLegarEmbarques::class,
        EnviaMensajeDeNuevaIncidenciaRegistrada::class,
        MensajeDeCapacidadDePatio::class,

        SendPlanForShipmentCommand::class,
        SendEventForShipmentCommand::class,

        GetEvidenceFromRControl::class,
        ClearLocationCommand::class,

        //		Interfaces a Wms Izel
        EnviarDatosIzelWmsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
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

        //      Comandos para interfaces a WMS Izel
        $schedule->command('izel:enviar-datos-izel-wms')->everyFiveMinutes();

        //      Comandos del módulo de Control de Patios
        $schedule->command('command:TiempoExcedidoParaSalirDePatio')->everyFifteenMinutes();
        //        Envia mensajes de a unidades sin llegada para embarques
        $schedule->command('command:UnidadesSinLLegarEmbarques')->everyMinute();
        //        Envío de mensajes de nuevas incidencias en embarques
        $schedule->command('command:EnviaMensajeDeNuevaIncidenciaRegistrada')->everyFiveMinutes();
        //        Envia mensaje de capacidad de patio superada al parámetro máximo
        $schedule->command('whatsapp:patiolleno')->everyTwoHours();

        //        Interfaces a recurso confiable
        $schedule->command('rc:SendPlanForShipmentCommand')->everyTwoHours();
        //        Enviar los eventos logísticos de la unidad a recurso confiable
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
