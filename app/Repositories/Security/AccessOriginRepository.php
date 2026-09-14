<?php

namespace App\Repositories\Security;

use App\Models\Configuration\AccessOrigins;
use Illuminate\Support\Collection;

class AccessOriginRepository
{
    public function getAllForUser(string $username): Collection
    {
        return AccessOrigins::with(['user:username,name', 'storageunittype:id,description'])
            ->where('username', $username)->get();
    }

    public function save(array $data, string $centerId)
    {
        $records = collect($data['origins'])->unique()
            ->map(static fn (int $originId) => ['centerid' => $centerId,
                'username' => $data['username'],
                'storageunittype_id' => $originId, ])->all();
        AccessOrigins::upsert($records, ['centerid', 'username', 'storageunittype_id'], []);
    }

    public function delete(int $id): void
    {
        $row = AccessOrigins::where('id', $id)->first();
        $row->delete();
    }
}
