<?php

namespace App\Traits\Web\Izel;

use App\Models\Configuration\EDI\SystemEventWebServiceIzel;

trait RegisterDataToIzel
{
    use WebServiceIzel;

    public function processIntefaceToIzel($receiptunit, $parameter, $type)
    {
        if (in_array('SWH-08', (array) $parameter)) {
            $reponse = $this->sendDataToWMSIzel($receiptunit, $type);
            if ($reponse->status() === 200) {
                $statusService = json_decode($reponse->getBody()->getContents());
                if ($statusService->{'Code'} === '0') {
                    SystemEventWebServiceIzel::createRegisterLog($receiptunit, $statusService, $parameter);
                } else {
                    SystemEventWebServiceIzel::createRegisterLog($receiptunit, $statusService, $parameter);
                    return redirect()->back()->with(session()->flash('error', 'Error al ingresar el folio en wms'));
                }
            }
        }
    }
}
