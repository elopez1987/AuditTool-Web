<?php

namespace App\Console\Commands\General;

use App\Mail\ArriveUnitToReceipt;
use App\Models\Configuration\Mails\StorageTaskMail;
use App\Models\Configuration\Mails\SystemDetailMail;
use App\Models\Receipt\ReceiptAsn;
use App\Traits\Web\General\GetParameters;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailForArriveUnitToReceipt extends Command
{
    use GetParameters;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email-for-arrive-unit-to-receipt';
    // SWH-30 => ENVIA EL CORREO DE LLEGADA DE PROVEEDOR EN EL REGISTRO DE UNIDAD

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia el correo de llegada de proveedor en el registro de unidad';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $taskMails = StorageTaskMail::where('statusid', '=', 'E')->where('mailid', 'MAIL-RECYMS')->get();
        if ($taskMails->isNotEmpty()) {
            foreach ($taskMails as $taskMail) {
                try {
                    $parameter = $this->getParametersOfWarehouse($taskMail->warehouseid);
                    if (! empty($parameter) && in_array('SWH-30', $parameter)) {
                        $receipt = ReceiptAsn::where('centerid', $taskMail->centerid)
                            ->where('warehouseid', $taskMail->warehouseid)
                            ->where('id', $taskMail->modelid)->first();

                        $contacts = SystemDetailMail::where('centerid', $taskMail->centerid)
                            ->where('warehouseid', $taskMail->warehouseid)
                            ->where('mailid', 'MAIL-RECYMS')->get()->toArray();

                        if (! empty($receipt)) {
                            $emails = array_column($contacts, 'email');
                            // Filtrar emails vacíos y validar que el array no esté vacío
                            $emails = array_filter($emails, function ($email) {
                                return ! empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
                            });

                            if (! empty($emails)) {
                                Log::info("Enviando email para receipt ID: {$receipt->id} a: " . implode(', ', $emails));
                                Mail::to($emails)->send(new ArriveUnitToReceipt($receipt));
                                Log::info("Email enviado exitosamente para receipt ID: {$receipt->id}");
                            } else {
                                Log::warning("No se encontraron emails válidos para el task mail ID: {$taskMail->id}, centerid: {$taskMail->centerid}, warehouseid: {$taskMail->warehouseid}");
                                Log::warning('Contacts encontrados: ' . json_encode($contacts));
                            }
                        } else {
                            Log::warning("No se encontró receipt para task mail ID: {$taskMail->id}, modelid: {$taskMail->modelid}");
                        }
                    }

                    // Marcar como procesado solo si no hubo errores
                    $taskMail->statusid = 'D';
                    $taskMail->save();

                } catch (Exception $e) {
                    Log::error("Error procesando task mail ID: {$taskMail->id} - " . $e->getMessage());
                    Log::error($e->getTraceAsString());

                    // En caso de error, marcar como procesado para evitar bucle infinito
                    // Puedes cambiar esto según tu lógica de negocio
                    $taskMail->statusid = 'D';
                    $taskMail->save();
                }
            }
        }
    }
}
