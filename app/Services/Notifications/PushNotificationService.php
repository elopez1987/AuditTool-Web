<?php

// app/Services/PushNotificationService.php

namespace App\Services\Notifications;

use App\Models\Configuration\DeviceToken;
use App\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class PushNotificationService
{
    /**
     * Envía a UN usuario específico (todos sus dispositivos: web + móvil)
     */
    public function sendToUser(User $user, string $title, string $body, array $data = [])
    {
        $tokens = $user->deviceTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            return; // el usuario no tiene ningún dispositivo registrado
        }

        $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * Envía a un GRUPO de usuarios (ej. todos los admins, todos los de una sucursal)
     */
    public function sendToUsers(iterable $users, string $title, string $body, array $data = [])
    {
        $userIds = collect($users)->pluck('id');

        $tokens = DeviceToken::whereIn('user_id', $userIds)
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return;
        }

        $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * El método base: manda el batch y limpia tokens muertos
     */
    protected function sendToTokens(array $tokens, string $title, string $body, array $data = [])
    {
        $message = CloudMessage::new()
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withData($data);

        $report = Firebase::messaging()->sendMulticast($message, $tokens);

        // Limpieza automática: si un token ya no sirve (app desinstalada, token expirado)
        // FCM lo marca como "invalid" y aquí lo borramos de la BD
        foreach ($report->invalidTokens() as $invalidToken) {
            DeviceToken::where('token', $invalidToken)->delete();
        }
    }

    /**
     * Envía por topic (broadcast masivo, ej. "promociones")
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = [])
    {
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withData($data);

        Firebase::messaging()->send($message);
    }
}
