<?php

declare(strict_types=1);

namespace App\Observers\Security;

use App\Models\Security\Users\StorageUserDevice;
use App\Services\Security\LocalSessionService;

final class LocalSessionDeviceObserver
{
    public function __construct(private readonly LocalSessionService $sessions) {}

    public function deleting(StorageUserDevice $device): void
    {
        $this->sessions->revokeDevice((int) $device->username, (int) $device->id);
    }

    public function updating(StorageUserDevice $device): void
    {
        if ($device->isDirty(['username', 'serialnumber'])) {
            $this->sessions->revokeDevice((int) $device->getOriginal('username'), (int) $device->id);
        }
    }
}
