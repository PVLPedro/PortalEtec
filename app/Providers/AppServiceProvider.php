<?php

namespace App\Providers;

use App\Listeners\CheckEmailDomainOnLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Event::listen(Login::class, CheckEmailDomainOnLogin::class);
        View::share('generalTextClass', 'text-text text-base/tight tracking-tight');

        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('lucide', [
                'path' => resource_path('svg'),
                'prefix' => 'lucide',
            ]);
        });
    }
}
