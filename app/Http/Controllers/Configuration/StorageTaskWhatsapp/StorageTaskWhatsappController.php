<?php

namespace App\Http\Controllers\Configuration\StorageTaskWhatsapp;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\WhatsApp\StorageTaskWhatsapp;

class StorageTaskWhatsappController extends BaseController
{
    public function index()
    {
        return view('Configuration.StorageTaskWhatsapp.index');
    }

    public function storagetaskwhatsapplist()
    {
        $data = StorageTaskWhatsapp::controlAccessUser()->with(['center:centerid,description', 'warehouse:warehouseid,name', 'status:statusid,name'])->get();

        return $this->showDataToView($data, 'Configuration.StorageTaskWhatsapp.partials.buttons');
    }

    public function destroy($id)
    {
        StorageTaskWhatsapp::destroy($id);

        return $this->redirectBackSuccessMessage('Registro eliminado con éxito.');
    }
}
