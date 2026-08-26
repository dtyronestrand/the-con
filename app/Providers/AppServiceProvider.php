<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Graph\GraphExtendSocialite;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->configureNativeDatabase();
        
        // Provide a fake request for native mode to prevent UrlGenerator errors
        if (isset($_SERVER['NATIVEPHP_RUNNING'])) {
            $this->app->instance('request', \Illuminate\Http\Request::create('http://127.0.0.1'));
        }
    }

    protected function configureNativeDatabase(): void
    {
        if (isset($_SERVER['NATIVEPHP_RUNNING'])) {
            $dbPath = storage_path('app/database.sqlite');
            config(['database.connections.sqlite.database' => $dbPath]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
        
        $this->configureDefaults();
        
        if (isset($_SERVER['NATIVEPHP_RUNNING'])) {
            $this->app->booted(function () {
                Artisan::call('migrate', ['--force' => true]);
            });
        }
        
        Event::listen(
            SocialiteWasCalled::class,
            GraphExtendSocialite::class.'@handle'
        );
    }


    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
