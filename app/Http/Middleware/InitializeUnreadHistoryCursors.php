<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use App\Models\StockAdjustment;
use App\Models\StockTransaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeUnreadHistoryCursors
{
    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->session();

        if (! $session->has(StockTransaction::LAST_VIEWED_SESSION_KEY)) {
            $session->put(
                StockTransaction::LAST_VIEWED_SESSION_KEY,
                StockTransaction::max('id') ?? 0
            );
        }

        if (! $session->has(ActivityLog::LAST_VIEWED_SESSION_KEY)) {
            $session->put(
                ActivityLog::LAST_VIEWED_SESSION_KEY,
                ActivityLog::max('id') ?? 0
            );
        }

        if (! $session->has(StockAdjustment::LAST_VIEWED_SESSION_KEY)) {
            $session->put(
                StockAdjustment::LAST_VIEWED_SESSION_KEY,
                StockAdjustment::max('id') ?? 0
            );
        }

        return $next($request);
    }
}
