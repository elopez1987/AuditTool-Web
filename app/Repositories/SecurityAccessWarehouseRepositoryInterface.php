<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface SecurityAccessWarehouseRepositoryInterface
{
    /**
     * The center/warehouse access records for the given user, with the
     * center/warehouse relations eager loaded (the view renders their names).
     */
    public function findForUser(string $username): Collection;

    /**
     * Clears the "default" flag on every access record for the user.
     */
    public function resetDefaultLevel(string $username): void;

    /**
     * Marks a single access record (scoped to the given username, so an id
     * belonging to another user can never be targeted) as the default.
     */
    public function setDefaultLevel(int $id, string $username): void;
}
