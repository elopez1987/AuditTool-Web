<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Security\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('securitypermissions.edit');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $permission = $this->route('securitypermission');

        return [
            'name' => ['required', 'string', 'max:200', 'regex:/^[a-zA-Z0-9_.-]+$/',
                Rule::unique('permissions', 'name')->where('guard_name', $permission->guard_name)->ignore($permission->id)],
            'moduleid' => ['required', 'integer', 'exists:securitymodules,moduleid'],
            'functionid' => ['required', 'integer',
                Rule::exists('securityfunctions', 'functionid')->where('moduleid', $this->input('moduleid'))],
            'description' => ['required', 'string', 'max:150'],
        ];
    }
}
