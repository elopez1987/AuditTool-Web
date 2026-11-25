<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Verification;

use App\Services\Receipt\VerificationSource;
use App\Services\Receipt\VerificationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'AsnId' => ['required', 'uuid'],
            'PartNumberCode' => ['required', 'string', 'max:255'],
            'Quantity' => ['bail', 'required', 'numeric', 'regex:/^\d{1,14}(?:\.\d{1,4})?$/', 'gt:0'],
            'TypeId' => ['required', Rule::enum(VerificationType::class)],
            'Source' => ['nullable', 'required_with:ExpectedLineId', Rule::enum(VerificationSource::class)],
            'ExpectedLineId' => ['nullable', 'required_with:Source', 'integer', 'min:1'],
            'ClientOperationId' => ['nullable', 'uuid'],
            'ProductionDate' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
            'ExpireDate' => ['nullable', 'date_format:Y-m-d', Rule::when(
                $this->filled('ProductionDate'),
                ['after_or_equal:ProductionDate'],
            )],
            'ExpireCode' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user() !== null;
    }
}
