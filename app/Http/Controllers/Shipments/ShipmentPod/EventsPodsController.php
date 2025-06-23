<?php

namespace App\Http\Controllers\Shipments\ShipmentPod;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\StorageShipmentsPods;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventsPodsController extends BaseController
{
    public function updatestatuspods($id, Request $request)
    {
        $file = StorageShipmentsPods::findOrFail($id);
        $file->statusid = $request['statusid'];
        $file->save();
        return $this->redirectBackSuccessMessage('Estatus actualizado con exito!!');
    }


    public function deletefilepod(Request $request)
    {
        DB::beginTransaction();
        try {
            $file = StorageShipmentsPods::where('recordid', $request['recordid'])->first();
            if (Storage::exists($file->pathfile)) {
                Storage::delete($file->pathfile);
            }
            $file->delete();
            DB::commit();
            return response()->json([
                'ServiceName' => 'deletefilepod',
                'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
                'Message' => 'Proceso efectuado correctamente',
            ], '200');
        } catch (Exception) {
            DB::rollback();
            return response()->json([
                'ServiceName' => 'deletefilepod',
                'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
                'Code' => '0',
                'Message' => 'Error al eliminar el registro.',
            ], '200');
        }
    }


}
