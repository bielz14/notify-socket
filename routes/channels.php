<?php

use Illuminate\Support\Facades\Broadcast;

/*
 * Personal notification channel — only the owner can subscribe
 */
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
