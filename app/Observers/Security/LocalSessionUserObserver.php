<?php

declare(strict_types=1);

namespace App\Observers\Security;

use App\Services\Security\LocalSessionService;
use App\User;

final class LocalSessionUserObserver
{
    public function __construct(private readonly LocalSessionService $sessions) {}

    public function updated(User $user): void
    {
        if ($user->wasChanged(['password', 'password_changed_at', 'statusid', 'deleted_at'])) {
            $this->sessions->revokeUser((int) $user->id);
        }
    }

    public function deleting(User $user): void
    {
        $this->sessions->revokeUser((int) $user->id);
    }
}
