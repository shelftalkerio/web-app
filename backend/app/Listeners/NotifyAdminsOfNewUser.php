<?php

namespace App\Listeners;

use App\Jobs\SendUserApprovalEmail;
use Illuminate\Auth\Events\Registered;

class NotifyAdminsOfNewUser
{
    public function handle(Registered $event)
    {
        $data = [
            'user' => $event->user->name,
            'email' => $event->user->email,
            'url' => url('/'),
        ];
        SendUserApprovalEmail::dispatch($data);
    }
}
