<?php

namespace App\Traits\Web\General;

use App\Models\General\EvidencePhoto;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait EvidencePhotoTrait
{
    use ValidatesImageUploads;

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
        self::validateImagePathSegments([$file]);
        $nombre = self::validatedImageName($request->file('file'));
        $path = storage_path().'/app/public/'.$file.'/'.$nombre;
        $pathdb = 'public/'.$file.'/'.$nombre;
        if ($path) {
            Image::make($request->file('file'))
                ->resize(1200, 1200, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('png')->save($path);

            return $pathdb;
        }
    }

    public function saveFileToModelBase64($file, $modelName, $model, $folder, $typeid, $location)
    {
        $this->validateImagePathSegments([$model->centerid, $folder]);
        if (! is_string($file) || strlen($file) > 7 * 1024 * 1024) {
            throw ValidationException::withMessages(['image' => 'Invalid image size.']);
        }
        $image = base64_decode(str_replace(' ', '+', preg_replace('/^data:image\/png;base64,/', '', $file)), true);
        if ($image === false || $image === '' || strlen($image) > 5 * 1024 * 1024) {
            throw ValidationException::withMessages(['image' => 'Invalid image content or dimensions.']);
        }
        try {
            $decodedImage = Image::make($image);
        } catch (NotReadableException) {
            throw ValidationException::withMessages(['image' => 'Invalid image content or dimensions.']);
        }
        if (! in_array($decodedImage->mime(), ['image/png', 'image/jpeg', 'image/webp'], true)
            || $decodedImage->width() > 4096 || $decodedImage->height() > 4096) {
            throw ValidationException::withMessages(['image' => 'Invalid image content or dimensions.']);
        }
        $imageName = Str::uuid().'.png';
        File::put(public_path('storage/public/'.$model->centerid.'/'.$folder.'/'.$imageName), (string) $decodedImage->encode('png'));
        $path = 'public/'.$model->centerid.'/'.$folder.'/'.$imageName;
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
