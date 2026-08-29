<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Note;
use App\Models\SavedLocation;
use App\Models\Service;
use App\Models\Task;
use App\Observers\SyncObserver;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use SocialiteProviders\Graph\GraphExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;

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

        // Queue local changes for push to the remote sync server. AppSetting is
        // deliberately excluded — it holds the local api_token and is pull-only.
        // Tag is also excluded: nothing in the app creates or attaches one yet
        // (the actual tagging feature is the plain `tags` json column on Note,
        // which already syncs as part of Note itself).
        Category::observe(SyncObserver::class);
        Service::observe(SyncObserver::class);
        SavedLocation::observe(SyncObserver::class);
        Note::observe(SyncObserver::class);
        Task::observe(SyncObserver::class);
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
