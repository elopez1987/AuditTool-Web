<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ReceiptDashboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'warehouseid' => ['nullable', 'string', 'max:50'],
            'providerid' => ['nullable', 'integer', 'exists:receiptproviders,id'],
            'operatorid' => ['nullable', 'integer', 'exists:storageoperators,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
}
