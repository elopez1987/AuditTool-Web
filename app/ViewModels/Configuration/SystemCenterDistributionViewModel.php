<?php

namespace App\ViewModels\Configuration;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use Illuminate\Support\Collection;

class SystemCenterDistributionViewModel
{
    /**
     * Builds the data for the edit view, preserving the exact variable name
     * ("systemcenterdistribution") already consumed by edit.blade.php and
     * partials/formEdit.blade.php.
     *
     * @return array{systemcenterdistribution: SystemCenterDistribution}
     */
    public function forEdit(SystemCenterDistribution $systemcenterdistribution): array
    {
        return compact('systemcenterdistribution');
    }

    /**
     * Data for Parameters/create.blade.php (SDC schema). Preserves the
     * exact variable names ("center", "parameters") already consumed there.
     *
     * @return array{center: SystemCenterDistribution, parameters: Collection}
     */
    public function forParameters(SystemCenterDistribution $center, Collection $parameters): array
    {
        return compact('center', 'parameters');
    }

    /**
     * Data for Parameters/createApp.blade.php (APP schema).
     *
     * @return array{center: SystemCenterDistribution, parameters: Collection}
     */
    public function forAppParameters(SystemCenterDistribution $center, Collection $parameters): array
    {
        return compact('center', 'parameters');
    }

    /**
     * Data for Notifications/create.blade.php (MCD schema).
     *
     * @return array{center: SystemCenterDistribution, parameters: Collection}
     */
    public function forNotifications(SystemCenterDistribution $center, Collection $parameters): array
    {
        return compact('center', 'parameters');
    }

    /**
     * Data for Folders/create.blade.php.
     *
     * @return array{center: SystemCenterDistribution, folders: Collection, directories: array}
     */
    public function forFolders(SystemCenterDistribution $center, Collection $folders, array $directories): array
    {
        return compact('center', 'folders', 'directories');
    }
}
