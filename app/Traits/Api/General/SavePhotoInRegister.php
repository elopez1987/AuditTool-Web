<?php

namespace App\Traits\Api\General;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait SavePhotoInRegister
{

    public function createNewEvidenceForRegister($file, $model, $module, $submodule, $folder)
    {
        $processedImage = $file;
        $processedImage = $this->processImage($processedImage);
        $imageName = $this->generateImageName();
        $directory = $this->createDirectoryPath($model->centerid, $module, $submodule, $folder);
        $fullPath = public_path('storage/' . $directory . $imageName);
        File::put($fullPath, base64_decode($processedImage));
        $relativePath = $directory . $imageName;
        return $relativePath;
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
    private function createDirectoryPath($centerId, $module, $submodel, $folder)
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $directory = 'public/' . $centerId . '/' . $year . '/' . $module . '/' . $submodel . '/' . $folder . '/';
        if (!Storage::exists($directory)) {
            \Storage::makeDirectory($directory);
        }
        return $directory;
    }

}
