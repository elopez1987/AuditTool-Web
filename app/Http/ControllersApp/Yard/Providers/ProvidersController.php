<?php

namespace App\Http\ControllersApp\Yard\Providers;

use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptProvider;
use App\Traits\ResponseApi;

class ProvidersController extends Controller
{
   use ResponseApi;

    public function getProvidersForCenter()
    {
        $provides = ReceiptProvider::controlAccessUser()
            ->where('statusid', '=', 'E')
            ->select(['centerid', 'id as providerid', 'name'])->get();
        return $this->getResponseSuccess('getProvidersForCenter', $provides);
    }


}
