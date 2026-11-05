<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Configuration\EdiToken;

use Illuminate\Support\Facades\Gate;

final class UpdateEdiTokenRequest extends CreateEdiTokenRequest
{
    public function authorize(): bool
    {
        return Gate::allows('editokens.edit');
    }

    /**
     * @return array<string, string|array<int, string>>
     */
    public function rules(): array
    {
        return array_replace(parent::rules(), [
            'client_secret' => ['sometimes', 'required', 'string', 'max:190'],
            'audience' => ['nullable', 'string', 'max:190'],
        ]);
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('client_secret')) {
            $this->getInputSource()->remove('client_secret');
        }
    }
}
