<?php

namespace App\Providers;

use App\Models\Semester;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Tapel;

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
        View::composer('layouts.navbar', function ($view) {
            $tapel = Tapel::where('status', '1')->first();
            $semester = Semester::where('status', '1')->first();
            $view->with(['tapel' => $tapel, 'semester' => $semester]);
        });
    }
}
