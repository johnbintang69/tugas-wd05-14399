<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
        // Set default string length untuk MySQL compatibility
        Schema::defaultStringLength(191);
        
        // Share common data to all views
        View::composer('*', function ($view) {
            $view->with([
                'appName' => config('app.name', 'Poliklinikikuk'),
                'appVersion' => '1.0.0',
            ]);
        });
        
        // Carbon locale to Indonesian
        \Carbon\Carbon::setLocale('id');
        
        // Custom blade directives for role checking
        \Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
        
        \Blade::if('dokter', function () {
            return auth()->check() && auth()->user()->isDokter();
        });
        
        \Blade::if('pasien', function () {
            return auth()->check() && auth()->user()->isPasien();
        });
        
        // Helper untuk format currency
        \Blade::directive('currency', function ($expression) {
            return "<?php echo 'Rp ' . number_format($expression, 0, ',', '.'); ?>";
        });
        
        // Helper untuk format tanggal Indonesia
        \Blade::directive('dateID', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->locale('id')->translatedFormat('d M Y'); ?>";
        });
        
        // Helper untuk format datetime Indonesia
        \Blade::directive('datetimeID', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->locale('id')->translatedFormat('d M Y H:i'); ?>";
        });
    }
}