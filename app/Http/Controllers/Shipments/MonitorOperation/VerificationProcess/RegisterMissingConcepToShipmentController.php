<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ShipmentControl;
use Illuminate\Http\Request;

class RegisterMissingConcepToShipmentController extends BaseController
{
    public function index(Request $request, $id)
    {
        $control = ShipmentControl::where('recordid', $id)->first();
        $control->missingconceptid = $request['missingconceptid'];
        $control->save();
        return $this->redirectBackSuccess();
    }
}
