<?php

namespace App\Http\Requests\Web\Configuration\CenterDistribution;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCenterNotificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parameter' => 'nullable|array',
            'parameter.*' => 'string',
        ];
    }
}
