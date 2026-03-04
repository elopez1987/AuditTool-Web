<?php

namespace App\Http\Controllers\Yard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Evidence\UploadImageRequest;
use App\Models\General\EvidencePhoto;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\General\EvidencePhotoTrait;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Storage;

class EvidencePhotoCheckOutController extends Controller
{
    use EvidencePhotoTrait;

    private const PHOTO_SH = 'SH';

    private const SUCCESS_MESSAGE = 'Fotografia eliminado con exito!!';

    public function uploadPhotoCheckOut(UploadImageRequest $request, $id)
    {
        $receiptunit = ReceiptUnit::findOrFail($id);
        $fileName = Str::random(10) . $request->file('file')->getClientOriginalName();
        [$pathbase, $path] = $this->generatePath($receiptunit->centerid, 'EvidenceOut', $fileName);
        if ($pathbase) {
            $this->addMediaToModel($request, $pathbase);
            $this->createEvidencePhoto('ReceiptUnit', $id, $path, 'OUT', $receiptunit);
        }
    }

    public function uploadphotocheckinprocess(UploadImageRequest $request, $id)
    {
        $receiptunit = ReceiptUnit::findOrFail($id);
        $fileName = Str::random(10) . $request->file('file')->getClientOriginalName();
        [$pathbase, $path] = $this->generatePath($receiptunit->centerid, 'EvidenceProcess', $fileName);
        if ($pathbase) {
            $this->addMediaToModel($request, $pathbase);
            $this->createEvidencePhoto('ReceiptUnit', $id, $path, 'IP', $receiptunit);
        }
    }

    public static function addMediaToModel($request, $pathbase)
    {
        Image::make($request->file('file'))
            ->resize(1200, 1200, function ($constraint) {
                $constraint->aspectRatio();
            })->save($pathbase);
    }

    public function downloadPhotoCheckOut()
    {
        return $this->downloadPhoto($id);
    }

    public function destroyPhotoCheckOut()
    {
        return $this->destroyPhoto($id);
    }

    public function downloadPhotoTireProcess($id)
    {
        return $this->downloadPhotoTire($id);
    }

    public function destroyPhotoTire($id)
    {
        return $this->destroyPhotoTire($id);
    }

    public function downloadPhotoInProcess($id)
    {
        return $this->downloadPhoto($id);
    }

    public function destroyPhotoInProcess($id)
    {
        return $this->destroyPhoto($id);
    }

    public function destroyPhotoIncidence($id)
    {
        $photo = EvidencePhoto::findOrFail($id);
        $photoSH = $this->findSHPhoto($photo->path);
        if (empty($photoSH)) {
            Storage::delete($photo->path);
        }
        EvidencePhoto::destroy($id);

        return redirect()->back()->with(session()->flash('success', self::SUCCESS_MESSAGE));
    }

    private function generatePath($centerId, $categoryName, $fileName)
    {
        $pathbase = implode(DIRECTORY_SEPARATOR, [storage_path(), 'app', 'public', $centerId, $categoryName, $fileName]);
        $path = implode(DIRECTORY_SEPARATOR, ['public', $centerId, $categoryName, $fileName]);

        return [$pathbase, $path];
    }

    private function findSHPhoto($path)
    {
        return EvidencePhoto::where('auxiliary01', '=', self::PHOTO_SH)
            ->where('path', '=', $path)->first();
    }
}
