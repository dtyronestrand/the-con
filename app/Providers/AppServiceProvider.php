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
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->configureNativeDatabase();
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
       $this->app->booted(function (){ if (!$this->app->runningInConsole() && file_exists(database_path('database.sqlite'))){
            Artisan::call('migrate', ['--force' => true]);
        }
       });
        $this->configureDefaults();
        $this->ensureNativeDatabase();
        
        Event::listen(
            SocialiteWasCalled::class,
            GraphExtendSocialite::class.'@handle'
        );
    }

    protected function ensureNativeDatabase(): void
    {
        if (isset($_SERVER['NATIVEPHP_RUNNING'])) {
            $dbPath = storage_path('app/database.sqlite');
            $needsMigration = !file_exists($dbPath);
            
            if ($needsMigration) {
                touch($dbPath);
            }
            
            try {
                DB::connection()->getPdo();
                DB::table('migrations')->exists();
            } catch (\Exception $e) {
                $needsMigration = true;
            }
            
            if ($needsMigration) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
        }
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
