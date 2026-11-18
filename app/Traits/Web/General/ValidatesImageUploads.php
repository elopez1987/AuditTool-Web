<?php

declare(strict_types=1);

namespace App\Traits\Web\General;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait ValidatesImageUploads
{
    protected static function validatedImageName(UploadedFile $file): string
    {
        Validator::make(['image' => $file], [
            'image' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:5120', 'dimensions:max_width=4096,max_height=4096'],
        ])->validate();

        return Str::uuid().'.png';
    }

    /**
     * @param  array<int, mixed>  $segments
     */
    protected static function validateImagePathSegments(array $segments): void
    {
        foreach ($segments as $segment) {
            if ((! is_string($segment) && ! is_int($segment))
                || ! preg_match('/^[a-zA-Z0-9_-]+$/D', (string) $segment)) {
                throw ValidationException::withMessages(['image' => 'Invalid image storage path.']);
            }
        }
    }
}
