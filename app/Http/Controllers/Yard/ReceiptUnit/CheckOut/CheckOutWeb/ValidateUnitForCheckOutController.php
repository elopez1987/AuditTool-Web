<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CheckOut\CheckOutWeb;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;

class ValidateUnitForCheckOutController extends BaseController
{

    public function index()
    {
        $this->authorize('registercheckout');
        return view('Yards.TransportOutPut.index');
    }

    public function checkoutvalidate(Request $request)
    {
        $receiptunit = ReceiptUnit::searchForBarcode($request['barcodebox']);
        if (empty($receiptunit)) {
            return $this->errorResponse('No existe numero de caja, favor de validar');
        } else {
            $parameterwarehouse = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            $parameter = $this->getParametersOfCarrier($receiptunit->carrierid);

            if ($receiptunit->razonstatusid == 1 || $receiptunit->razonstatusid == 2) {
                if (in_array('SCR-01', (array)$parameter)) {
                    return $this->redirectOutput($receiptunit);
                } else {
                    if (in_array('SWH-22', (array)$parameterwarehouse)) {
                        return $this->redirectOutput($receiptunit);
                    } else {
                        return $this->validateStatusBox($receiptunit);
                    }
                }
            }
            if ($receiptunit->razonstatusid == 3) {
                return $this->redirectOutput($receiptunit);
            }
        }
    }

    private function validateStatusBox($receiptunit)
    {
        if ($receiptunit->statusid != 'OU') {
            return $this->errorResponse('Falta autorización de salida!!');
        } else {
            return redirect('authorizetransportoutput/' . $receiptunit->id . '/edit');
        }
    }

    private function redirectOutput($receiptunit)
    {
        return redirect('authorizetransportoutput/' . $receiptunit->id . '/edit');
    }


}
