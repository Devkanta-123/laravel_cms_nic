<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;
use App\Models\AuditLog;
class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        AuditLog::create([
            'user_id'    => $event->user->id,
            'action'     => 'logout',
            'status'     => 'success',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}