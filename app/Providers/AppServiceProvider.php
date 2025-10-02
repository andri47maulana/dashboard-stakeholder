<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\ServiceProvider;

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
        //
        // Inject data regional ke sidebar
    // View::composer('layouts.sidebar', function ($view) {
    //     $regionals = DB::table('tb_unit')
    //         ->select('region')
    //         ->distinct()
    //         ->orderBy('region')
    //         ->pluck('region');

    //     $view->with('regionals', $regionals);
    // });
    }
}
