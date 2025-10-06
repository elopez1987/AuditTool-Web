<?php

namespace App\Traits\Web\General;

use App\Models\General\EvidencePhoto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait StorageWebPhotoTrait
{
    //    Esta funcion guarda la imagen y devuelve el path para guardarlo en una columna de la tabla
    //    $file = El archivo a guardar (Imagen)
    //    $centerid = Representa el centro de distribucion para guardar la imagen
    //    $module = Representa el módulo principal del sistema
    //    $submodule = Representa a la funcion correspondiente al módulo
    //    $folder = Es el directorio final donde se guardara la imagen

    public function saveFileToModel($file, $centerid, $module, $submodule, $folder)
    {
        $imageName = Str::random(10) . $file->getClientOriginalName();
        $directory = $this->createDirectoryPath($centerid, $module, $submodule, $folder);
        $basePath = public_path('storage/' . $directory . $imageName);
        $this->resizeImage($file, $basePath);

        return $directory . $imageName;
    }

    public function resizeImage($file, $basePath)
    {
        Image::make($file)
            ->resize(1200, 1200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($basePath);
    }

    //    $modeltype = Representa el tipo de modelo
    //    $id = Representa él, id del modelo
    //    $path = Representa el path de la imagen
    //    $type = Representa el tipo de imagen
    //    $model = Representa la instancia del modelo que se está procesando

    public function createEvidencePhoto($modeltype, $id, $path, $type, $model)
    {
        EvidencePhoto::create([
            'model_type' => $modeltype,
            'model_id' => $id,
            'path' => $path,
            'createuser' => Auth::user()->id,
            'auxiliary01' => $type,
            'auxiliary02' => $model->centerid,
            'auxiliary03' => $model->warehouseid,
        ]);
    }

    private function createDirectoryPath($centerId, $module, $submodule, $folder)
    {
        $year = Carbon::now()->year;
        $directory = 'public/' . $centerId . '/' . $year . '/' . $module . '/' . $submodule . '/' . $folder . '/';
        if (! Storage::exists($directory)) {
            \Storage::makeDirectory($directory);
        }

        return $directory;
    }
}
