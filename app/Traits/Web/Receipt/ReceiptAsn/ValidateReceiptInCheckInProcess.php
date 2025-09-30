<?php

namespace App\Traits\Web\Receipt\ReceiptAsn;

use App\Models\Receipt\ReceiptAsn;

trait ValidateReceiptInCheckInProcess
{
    public function validateReceiptInCheckIn($request, $parameter)
    {
        $receipt = ReceiptAsn::searchReceiptInCheckIn($request['shipmentid']);
        if ($receipt === null) {
            return $this->errorResponse('El número de recibo no se encuentra programado, favor de validar.');
        }

        if (!empty($receipt) && $receipt->providerid !== $request['providerid']) {
            return $this->errorResponse('El proveedor es diferente al programado, favor de validar.');
        }
    }


}
