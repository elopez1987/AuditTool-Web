<?php

namespace App\Policies\Configuration;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\User;

class SystemCenterDistributionPolicy
{
    /**
     * These delegate to the exact permission strings already used by the
     * controller and the Blade views (systemcenterdistributions.*), so
     * existing role/permission assignments keep working unchanged.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('systemcenterdistributions.index');
    }

    public function create(User $user): bool
    {
        return $user->can('systemcenterdistributions.create');
    }

    public function update(User $user, SystemCenterDistribution $systemcenterdistribution): bool
    {
        return $user->can('systemcenterdistributions.edit');
    }

    public function parametrize(User $user, SystemCenterDistribution $systemcenterdistribution): bool
    {
        return $user->can('systemcenterdistributions.parametrizar');
    }

    public function parametrizeApp(User $user, SystemCenterDistribution $systemcenterdistribution): bool
    {
        return $user->can('systemcenterdistributions.parametrizarapp');
    }

    public function notify(User $user, SystemCenterDistribution $systemcenterdistribution): bool
    {
        return $user->can('systemcenterdistributions.notification');
    }

    public function manageFolders(User $user, SystemCenterDistribution $systemcenterdistribution): bool
    {
        return $user->can('systemcenterdistributions.folder');
    }
}
