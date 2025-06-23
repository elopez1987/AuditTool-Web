<?php

namespace App\Traits\Api\General;

use App\Models\General\EvidencePhoto;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait EvidenceFileImage
{

//    Esta funcion guarda el registro directamente en la tabla de storageevidencephotos

//        $file = Evidencia en formato base64
//        $model = Instancia del modelo que se está procesando
//        $type = Tipo de registro de la evidencia (IN, OUT, DES, SHT)
//        $modeltype = Nombre del modelo de referencia (ReceiptUnit, ReceiptAsn, OrderDelivery)
//        $module = Indica el módulo donde se está procesando la solicitud (Patios, Embarques, Recibo, Mantenimiento)
//        $folder = Nombre del directorio de destino, se usara como subdirectorio;

//Ejamplo
//    $this->createNewEvidence($request['file'],$receiptUnit,'IN','ReceiptUnit','Yard','CheckIn',$request);

    public function createNewEvidence($file, $model, $type, $modeltype, $module, $folder,$request): void
    {
        $processedImage = $file;
        $processedImage = $this->processImage($processedImage);
        $imageName = $this->generateImageName();
        $directory = $this->createDirectoryPath($model->centerid, $module, $modeltype, $folder);
        $fullPath = public_path('storage/' . $directory . $imageName);
        File::put($fullPath, base64_decode($processedImage));
        $relativePath = $directory . $imageName;
        $this->createEvidencePhoto($modeltype, $model->id, $relativePath, $type, $model,$request);
    }

    // Extracted helper function for processing the image
    private function processImage($image)
    {
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        return $image;
    }

// Extracted helper function for generating the image name
    private function generateImageName()
    {
        return Str::random(25) . '.png';
    }

// Extracted helper function for creating the directory path
    private function createDirectoryPath($centerId, $module, $submodule, $folder)
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $directory = 'public/' . $centerId . '/' . $year . '/'  . $module . '/' . $submodule . '/' . $folder . '/';
        if (!Storage::exists($directory)) {
            \Storage::makeDirectory($directory);
        }
        return $directory;
    }

    private function createEvidencePhoto($modeltype, $id, $path, $type, $model,$request)
    {
        EvidencePhoto::create([
            'centerid' => $model->centerid,
            'warehouseid' => $model->warehouseid,
            'model_type' => $modeltype,
            'model_id' => $id,
            'path' => $path,
            'auxiliary01' => $type,
            'auxiliary02' => $model->centerid,
            'auxiliary03' => $model->warehouseid,
            'auxiliary05' => $request['type'] ?? null,
        ]);
    }


}
