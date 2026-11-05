<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Configuration\TablesDataBase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

final class UpdateSystemTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('systemtables.edit');
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:4000'],
        ];
    }
}
