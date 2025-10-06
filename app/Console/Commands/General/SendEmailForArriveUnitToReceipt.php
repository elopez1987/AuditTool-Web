<?php

namespace App\Console\Commands\General;

use App\Mail\ArriveUnitToReceipt;
use App\Models\Configuration\Mails\StorageTaskMail;
use App\Models\Configuration\Mails\SystemDetailMail;
use App\Models\Receipt\ReceiptAsn;
use App\Traits\Web\General\GetParameters;
use Illuminate\Console\Command;
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
                        Mail::to($emails)->send(new ArriveUnitToReceipt($receipt));
                    }
                }
                $taskMail->statusid = 'D';
                $taskMail->save();
            }
        }
    }
}
