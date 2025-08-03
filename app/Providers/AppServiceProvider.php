<?php

namespace App\Providers;

use App\Charts\AttendancesChart;
use App\Charts\PerformanceChart;
use App\Models\Access;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        View::composer('*', function ($view) {
            if (auth()->check()) {
                $accesses = Access::where('role_id', auth()->user()->role_id)
                    ->with('menu')
                    ->whereHas('menu', function($query) {
                        $query->where('is_active', true);
                    })
                    ->get();
                return $view->with('accesses', $accesses);
            }
        });
    }
}
