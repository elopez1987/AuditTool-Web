<?php

namespace App\Jobs\Notifications;

use App\Services\Notifications\PushNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUsersByTypeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $typeKey,
        public array $userIds,
        public string $title,
        public string $body,
        public array $data = [],
    ) {}

    public function handle(PushNotificationService $pushNotificationService): void
    {
        $pushNotificationService->sendTypeToUsers($this->typeKey, $this->userIds, $this->title, $this->body, $this->data);
    }

    // ##### Ejemplo de usuo #####
    //		NotifyUsersByTypeJob::dispatch(
    //		'yard_alert',
    //		User::where('warehouse_id', $warehouse->id)->pluck('id')->toArray(),
    //		'Alerta de patio',
    //		'Camión detenido más de 2 horas en el andén 4',
    //		['dock_id' => (string) $dock->id]
    //		);

}
