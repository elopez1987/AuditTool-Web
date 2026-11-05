<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Security\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

final class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('securityroles.edit');
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:99'],
            'menustrucid' => ['required', 'string', 'exists:securitymenustructure,menustrucid'],
            'statusid' => ['required', 'string', 'max:10'],
        ];
    }
}
