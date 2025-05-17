<?php

namespace App\Jobs;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable; // ✅ Required for dispatch()
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserApprovalMail;

class SendUserApprovalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        // Send approval request email to all admins
        $admins = User::where('role', UserRole::SuperAdmin)->get()->unique('id');

//        dd($admins);

        foreach ($admins as $admin) {
            \Log::info($admin);
            Mail::to($admin->email)->queue(new UserApprovalMail($this->data));
        }
    }
}
