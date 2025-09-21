<?php

namespace App\Http\Requests\Web\Configuration\CenterDistribution;

use Illuminate\Foundation\Http\FormRequest;

class CreateCenterDistributionRequest extends FormRequest
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
            'capacityyard' => 'required|numeric|max:100|min:1',
            'code' => 'nullable|string|max:15',
            'formatinvoice' => 'required|string|max:20',
            'auxiliary01' => 'nullable|string|max:50',
            'auxiliary02' => 'nullable|string|max:50',
            'auxiliary03' => 'nullable|string|max:50',
            'auxiliary04' => 'nullable|string|max:50',
            'auxiliary05' => 'nullable|string|max:50',
            'url' => 'nullable|string|max:100',
            'userapi' => 'nullable|string|max:50',
            'passwordapi' => 'nullable|string|max:50',
        ];
    }
}
