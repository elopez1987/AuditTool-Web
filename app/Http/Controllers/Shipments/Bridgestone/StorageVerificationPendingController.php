<?php

namespace App\Http\Controllers\Shipments\Bridgestone;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\Bridgestone\Verificacion\Storageverificationpending;

class StorageVerificationPendingController extends BaseController
{
    public function update($id)
    {
        StorageVerificationPending::where('id', $id)->update(['statusid' => 'CVB-03']);

        return $this->redirectBackSuccessMessage('Registro actualizado con exito!!');
    }
}
