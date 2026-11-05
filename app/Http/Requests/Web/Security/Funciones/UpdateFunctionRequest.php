<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Security\Funciones;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class UpdateFunctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('securityfunctions.edit');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'moduleid' => ['required', 'integer', 'exists:securitymodules,moduleid'],
            'description' => ['required', 'string', 'max:100'],
            'route' => ['sometimes', 'array', 'max:5'],
            'route.*' => ['required', 'string', 'distinct', Rule::in(['index', 'create', 'show', 'edit', 'destroy'])],
            'name' => ['nullable', 'required_with:route', 'string', 'max:180', 'regex:/^[a-zA-Z0-9_.-]+$/'],
            'typeid' => ['required_with:route', Rule::in(['web', 'api'])],
        ];
    }
}
