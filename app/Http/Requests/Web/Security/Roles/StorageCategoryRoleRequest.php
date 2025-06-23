<?php

namespace App\Http\Requests\Web\Security\Roles;

use Illuminate\Foundation\Http\FormRequest;

class StorageCategoryRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'categoryincid' => 'required',
            'categoryincdetailid' => 'required',
        ];
    }
}
