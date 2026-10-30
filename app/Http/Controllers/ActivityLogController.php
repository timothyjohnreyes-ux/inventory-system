<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $request->session()->put(
            ActivityLog::LAST_VIEWED_SESSION_KEY,
            ActivityLog::max('id') ?? 0
        );

        $query = ActivityLog::query()->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%");
            });
        }

        if ($request->filled('module')) {
            $query->where('module', $request->input('module'));
        }

        $activityLogs = $query
            ->paginate(10)
            ->withQueryString();

        $modules = ActivityLog::query()
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return view('activity-logs.index', compact(
            'activityLogs',
            'modules'
        ));
    }
}
