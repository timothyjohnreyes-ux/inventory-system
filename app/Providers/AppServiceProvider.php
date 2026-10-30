<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ViewFacade::composer('layouts.app', function (View $view): void {
            $session = request()->session();

            $unreadStockTransactionCount = StockTransaction::query()
                ->where('id', '>', $session->get(StockTransaction::LAST_VIEWED_SESSION_KEY))
                ->count();

            $unreadActivityLogCount = ActivityLog::query()
                ->where('id', '>', $session->get(ActivityLog::LAST_VIEWED_SESSION_KEY))
                ->count();

            $view->with([
                'unreadStockTransactionCount' => $unreadStockTransactionCount,
                'unreadActivityLogCount' => $unreadActivityLogCount,
            ]);
        });
    }
}
