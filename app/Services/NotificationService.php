<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function send($userId, $title, $message, $url = null, $dispositionId = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'disposition_id' => $dispositionId,
            'title' => $title,
            'message' => $message,
            'url' => $url
        ]);
    }
}