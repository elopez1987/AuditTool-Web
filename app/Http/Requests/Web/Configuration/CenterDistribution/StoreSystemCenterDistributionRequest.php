<?php

namespace App\Http\Requests\Web\Configuration\CenterDistribution;

use Illuminate\Foundation\Http\FormRequest;

class StoreSystemCenterDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'centerid' => 'required|string|max:15|unique:systemcenterdistributions,centerid',
            'description' => 'required|string|max:50',
            'name' => 'required|string|max:50',
            'auxiliary01' => 'nullable|string|max:50',
            'auxiliary02' => 'nullable|string|max:50',
            'auxiliary03' => 'nullable|string|max:50',
        ];
    }
}
