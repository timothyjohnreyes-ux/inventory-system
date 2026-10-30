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

        $dateRules = [
            'start_date' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
        ];

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $dateRules['end_date'][] = 'after_or_equal:start_date';
        }

        $dateFilters = $request->validate($dateRules);
        $startDate = $dateFilters['start_date'] ?? '';
        $endDate = $dateFilters['end_date'] ?? '';

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

        if ($dateFilters['start_date'] ?? null) {
            $query->whereDate('created_at', '>=', $dateFilters['start_date']);
        }

        if ($dateFilters['end_date'] ?? null) {
            $query->whereDate('created_at', '<=', $dateFilters['end_date']);
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
            'modules',
            'startDate',
            'endDate'
        ));
    }
}
