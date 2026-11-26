<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateReceiptStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'numeric', 'regex:/^\d{1,14}(?:\.\d{1,4})?$/'],
            'expirecode' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user() !== null;
    }
}
