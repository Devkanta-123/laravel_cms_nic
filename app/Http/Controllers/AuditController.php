<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Carbon\Carbon;

class AuditController extends Controller
{
    public function getAuditLogs()
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->get();
        $logs->transform(function ($log) {
            $log->formatted_created_at = Carbon::parse($log->created_at)->format('d M Y, h:i A');
            return $log;
        });
        return response()->json([
            'status' => 'success',
            'data' => $logs
        ]);
    }


}
