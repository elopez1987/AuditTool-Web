<?php

namespace App\Http\ControllersApp\Yard\LabelTruck;

use App\Http\Controllers\Controller;
use App\Http\Resources\Yard\StorageLabelTruckResource;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class LabelTruckController extends Controller
{
    use ResponseApi;

    public function getLabelTruckStock(Request $request)
    {
        if ($request['barcodebox'] == null) {
            return $this->getResponseWithErrors('getLabelTruckStock', 'El número de código de barra es obligatorio');
        }
        $data = StorageLabelTruck::controlAccessUser()
            ->where('statusid', '=', 'E')
            ->where('barcode', $request['barcodebox'])
            ->first();
        return $this->showOne('getLabelTruckStock',new StorageLabelTruckResource($data));
    }
}
