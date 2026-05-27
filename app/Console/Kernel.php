<?php

namespace App\Console;

use App\Console\Commands\General\ClearLocationCommand;
use App\Console\Commands\General\CloseValidateQRReceipt;
use App\Console\Commands\General\DeleteHistLogWebServiceIzel;
use App\Console\Commands\General\DeleteLogWhatsApp;
use App\Console\Commands\Izel\SendDataToIzelWmsV5Command;
use App\Console\Commands\Receipt\ClearReceiptAsnCommand;
use App\Console\Commands\RecursoConfiable\GetEvidenceFromRControl;
use App\Console\Commands\RecursoConfiable\ObtenerETAdeRecursoConfiable;
use App\Console\Commands\RecursoConfiable\SendEventForShipmentCommand;
use App\Console\Commands\RecursoConfiable\SendPlanForShipmentCommand;
use App\Console\Commands\Yard\MensajeDeCapacidadDePatio;
use App\Console\Commands\Yard\TiempoExcedidoParaSalirDePatio;
use App\Console\Commands\Yard\UnidadesSinLLegarEmbarques;
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
        MensajeDeCapacidadDePatio::class,

        SendPlanForShipmentCommand::class,
        SendEventForShipmentCommand::class,

        GetEvidenceFromRControl::class,
        ClearLocationCommand::class,

        //		Interfaces a Wms Izel
        //        EnviarDatosIzelWmsCommand::class,
        SendDataToIzelWmsV5Command::class,
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
        $schedule->command('command:CloseValidateQRReceipt')->everyTwoHours();
        $schedule->command('whatsapp:deletelog')->daily();
        $schedule->command('clear:receiptasn')->dailyAt('01:00');

        //      Comandos para interfaces a WMS Izel
        //        $schedule->command('izel:enviar-datos-izel-wms')->everyFiveMinutes();
        $schedule->command('izel:send-data-izel-wms-v5')->everyFiveMinutes();

        //      Comandos del módulo de Control de Patios

        //		Envia mensaje de unidades sin salida de patio por tiempo excedido por autorización
        $schedule->command('command:TiempoExcedidoParaSalirDePatio')->everyFifteenMinutes();
        //        Envia mensajes de a unidades sin llegada para embarques
        $schedule->command('command:UnidadesSinLLegarEmbarques')->hourly();

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
        $schedule->command('system:clearlocation')->everyFifteenMinutes();
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
