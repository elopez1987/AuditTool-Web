<?php

namespace App\Console\Commands\Izel;

use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
use App\Models\Shipments\EdiShipment;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\Izel\WebServiceIzel;
use Illuminate\Console\Command;

class SendDataToWmsIzel extends Command
{
    use GetParameters;
    use WebServiceIzel;

    protected $signature = 'command:SendDataToWmsIzel';

    protected $description = 'Envia la informacion de los folios de entrada y salida de las unidades a wms izel';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $folios = $this->getShipments();
        if (! empty($folios)) {
            foreach ($folios as $folio) {
                $receiptunit = ReceiptUnit::where('id', $folio->shipmentid)->first();
                $parameter = $this->getParametersOfCenter($receiptunit->centerid);
                $reponse = $this->sendDataToWMSIzel($receiptunit, $folio->typeMovement);
                $statusService = json_decode($reponse->getBody()->getContents());
                $this->LogVersion3($statusService, $receiptunit, $parameter, $folio);
                $this->LogVersion4($parameter, $statusService, $receiptunit, $folio);
            }
        }
    }

    public function LogVersion4($parameter, $statusService, ReceiptUnit $receiptunit, $folio): void
    {
        if (in_array('SDC-07', (array) $parameter)) {
            if ($statusService->Code === '200') {
                SystemEventWebServiceIzel::createRegisterLog($receiptunit, $statusService, $parameter);
                EdiShipment::destroy($folio->id);
            } else {
                SystemEventWebServiceIzel::createRegisterLogError($receiptunit, $statusService, $parameter);
                $folio->quantity++;
                $folio->message = $statusService->Description;
                $folio->save();
                if ($folio->quantity > 12) {
                    EdiShipment::destroy($folio->id);
                }
            }
        }
    }

    public function LogVersion3($statusService, ReceiptUnit $receiptunit, $parameter, $folio): void
    {
        if ($statusService->{'Code'} === '0') {
            SystemEventWebServiceIzel::createRegisterLog($receiptunit, $statusService, $parameter);
            EdiShipment::destroy($folio->id);
        } else {
            SystemEventWebServiceIzel::createRegisterLog($receiptunit, $statusService, $parameter);
            $folio->quantity++;
            $folio->message = $statusService->{'Message'};
            $folio->save();
            if ($folio->quantity > 12) {
                EdiShipment::destroy($folio->id);
            }
        }
    }

    public function getShipments()
    {
        return EdiShipment::where('type', '=', 'IZEL')
            ->select(['centerid', 'shipmentid', 'auxiliary01 as typeMovement', 'quantity', 'message', 'id'])->get();
    }
}
