<?php

namespace App\Providers;

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

            if (! $session->has(StockTransaction::LAST_VIEWED_SESSION_KEY)) {
                $session->put(
                    StockTransaction::LAST_VIEWED_SESSION_KEY,
                    StockTransaction::max('id') ?? 0
                );
            }

            $unreadStockTransactionCount = StockTransaction::query()
                ->where('id', '>', $session->get(StockTransaction::LAST_VIEWED_SESSION_KEY))
                ->count();

            $view->with('unreadStockTransactionCount', $unreadStockTransactionCount);
        });
    }
}
