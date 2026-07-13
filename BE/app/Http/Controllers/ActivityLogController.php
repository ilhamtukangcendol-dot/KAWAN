<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(25);
        return view('activity-logs.index', compact('logs'));
    }

    public function clear()
    {
        ActivityLog::truncate();
        ActivityLog::log('Clear Activity Logs', 'Membersihkan riwayat log aktivitas pengguna');

        return redirect()->route('activity-logs.index')->with('success', 'Riwayat aktivitas log berhasil dibersihkan.');
    }
}
