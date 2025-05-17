<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Jobs\SendUserApprovalEmail;

class NotifyAdminsOfNewUser
{
    public function handle(Registered $event)
    {
        $data = [
            'user' => $event->user->name,
            'email' =>  $event->user->email,
            'url' =>  url('/')
        ];
        SendUserApprovalEmail::dispatch($data);
    }
}
