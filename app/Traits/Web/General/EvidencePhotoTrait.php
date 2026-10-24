<?php

namespace App\Traits\Web\General;

use App\Models\General\EvidencePhoto;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait EvidencePhotoTrait
{
    public function downloadPhoto($id): StreamedResponse
    {
        $photo = EvidencePhoto::findOrFail($id);

        return Storage::download($photo->path);
    }

    public function destroyPhoto($id): RedirectResponse
    {
        $photo = EvidencePhoto::findOrFail($id);
        Storage::delete($photo->path);
        EvidencePhoto::destroy($id);

        return redirect()->back()->with(session()->flash('success', 'Fotografia eliminado con exito!!'));
    }



    public static function addMediaToModel(Request $request, $file)
    {
        $nombre = Str::random(10) . $request->file('file')->getClientOriginalName();
        $path = storage_path() . '/app/public/' . $file . '/' . $nombre;
        $pathdb = 'public/' . $file . '/' . $nombre;
        if ($path) {
            Image::make($request->file('file'))
                ->resize(1200, 1200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);

            return $pathdb;
        }
    }

    public function saveFileToModelBase64($file, $modelName, $model, $folder, $typeid, $location)
    {
        $image = $file;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $model->id . '-' . Str::random(15) . '.png';
        File::put(public_path('storage/public/' . $model->centerid . '/' . $folder . '/' . $imageName), base64_decode($image));
        $path = 'public/' . $model->centerid . '/' . $folder . '/' . $imageName;
        if ($location === 'ModelEvidence') {
            self::createEvidencePhoto($modelName, $model->id, $path, $typeid, $model);
        } else {
            return $path;
        }
    }

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
}
