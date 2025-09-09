<?php

namespace App\Console\Commands\Bridgestone;

use App\Mail\Bridgestone\DifferenceWeightUnitMail;
use App\Models\Configuration\Mails\StorageTaskMail;
use App\Models\Configuration\Mails\SystemDetailMail;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMailForDifferenceWeightCommand extends Command
{
    protected $signature = 'send:mail-for-difference-weight';

    protected $description = 'Envia el correo para notificar la diferencia de peso en el registro de salida de la unidad';

    public function handle(): void
    {
        $taskMails = StorageTaskMail::where('statusid', '=', 'E')->where('mailid', 'MAIL-WEIGHT')->get();
        if ($taskMails->isNotEmpty()) {
            foreach ($taskMails as $taskMail) {
                $receiptUnit = ReceiptUnit::where('centerid', $taskMail->centerid)
                    ->where('warehouseid', $taskMail->warehouseid)->where('id', $taskMail->modelid)->first();
                if (!empty($receiptUnit)) {
                    $contacts = SystemDetailMail::where('centerid', $taskMail->centerid)
                        ->where('warehouseid', $taskMail->warehouseid)
                        ->where('mailid', 'MAIL-WEIGHT')->get()->toArray();
                    if (!empty($contacts) && !empty($receiptUnit)) {
                        $emails = array_column($contacts, 'email');
                        Mail::to($emails)->send(new DifferenceWeightUnitMail($receiptUnit));
                    }
                }
                $taskMail->statusid = 'D';
                $taskMail->save();
            }
        }
    }
}
