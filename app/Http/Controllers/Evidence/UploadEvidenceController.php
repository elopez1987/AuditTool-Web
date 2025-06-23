<?php

namespace App\Http\Controllers\Evidence;

use App\Http\Controllers\BaseController;
use App\Models\Evidence\StorageEvidenceProcess;
use App\Traits\EvidencePhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UploadEvidenceController extends BaseController
{
    use EvidencePhotoTrait;
    public function uploadevidenceshipment($id)
    {
        $storageevidenceprocess = StorageEvidenceProcess::findOrFail($id);
        return view('Evidence.StorageEvidences.uploadEvidence', compact('storageevidenceprocess'));
    }

    public function uploadphotoshipment($id, Request $request)
    {
        $model = StorageEvidenceProcess::findOrFail($id);
        $nameOriginal = str_replace(" ", "_", $request->file('file')->getClientOriginalName());
        $nombre = Str::random(10) . $nameOriginal;
        $pathbase = storage_path() . '/app/public/' . $model->centerid . '/EvidenceProcess/' . $nombre;
        if ($pathbase) {
            $this->addMediaToModel($request, $pathbase);
            $path = 'public/' . $model->centerid . '/EvidenceProcess/' . $nombre;
            $this->createEvidencePhoto('StorageEvidenceProcess', $id, $path, 'PR', $model);
        }
    }

    public static function addMediaToModel($request, $pathbase)
    {
        Image::make($request->file('file'))
            ->resize(1200, 1200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($pathbase);
    }
}
