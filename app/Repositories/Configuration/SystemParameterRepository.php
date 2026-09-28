<?php

namespace App\Repositories\Configuration;

use App\Models\Configuration\Parameters\SystemParameter;
use Illuminate\Database\Eloquent\Builder;

class SystemParameterRepository
{
    public function all(array $data): Builder
    {
        return SystemParameter::with('status:statusid,name')
            ->when($data['name'] ?? null, function (Builder $query, string $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($data['parameterid'] ?? null, function (Builder $query, string $parameterid) {
                $query->where('parameterid', 'like', "%{$parameterid}%");
            })
            ->select('systemparameters.*');
    }

    public function store(array $data): SystemParameter
    {
        return SystemParameter::create($data);
    }

    public function update(array $data): SystemParameter
    {
        return SystemParameter::update($data);
    }

    public function destroy(SystemParameter $systemParameter)
    {
        $systemParameter->update([
            'statusid' => 'D',
        ]);

        $systemParameter->delete();
    }
}
