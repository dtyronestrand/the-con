<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use App\Models\AppSetting;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
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

        // CUSTOM AUTHENTICATION LOGIC
        Fortify::authenticateUsing(function (Request $request) {
            $validated = $request->validate([
                Fortify::username() => 'required|string',
                'password' => 'required|string',
            ]);

            $email = $validated[Fortify::username()];
            $password = $validated['password'];

            // 1. Try REMOTE Login (API)
            // We do this first to ensure we have the latest data and token.
            try {
                $baseUrl = env('API_URL');
                
                // Short timeout (2s) so offline users don't wait long
                $response = Http::timeout(2)->post("{$baseUrl}/api/login", [
                    'email' => $email,
                    'password' => $password,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $token = $data['token'];

                    // A. Sync Remote Success to Local Database
                    // This creates the user locally if they don't exist,
                    // or updates their password if they changed it on the web.
                    $user = User::updateOrCreate(
                        ['email' => $email],
                        [
                            'name' => 'App User', // You can fetch real name if API sends it
                            'password' => Hash::make($password),
                            'email_verified_at' => now(),
                        ]
                    );

                    // B. Save the API Token
                    AppSetting::updateOrCreate(
                        ['key' => 'api_token'],
                        [
                            'value' => $token,
                            'uuid' => (string) Str::uuid()
                        ]
                    );

                    Log::info("Login: Remote auth successful. Token saved.");
                    return $user;
                }
            } catch (\Exception $e) {
                Log::warning("Login: Remote auth failed (Offline?): " . $e->getMessage());
            }

            // 2. Fallback to LOCAL Login (SQLite)
            // If we are here, either the API is down/offline, or the credentials failed remotely.
            // We check if we have a local user with these credentials.
            $user = User::where('email', $email)->first();

            if ($user && Hash::check($password, $user->password)) {
                Log::info("Login: Local auth successful (Offline mode).");
                return $user;
            }

            // 3. Fail
            return null;
        });
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn (Request $request) => Inertia::render('auth/Login', [
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
            'canRegister' => Features::enabled(Features::registration()),
            'status' => $request->session()->get('status'),
        ]));

        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]));

        Fortify::requestPasswordResetLinkView(fn (Request $request) => Inertia::render('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::verifyEmailView(fn (Request $request) => Inertia::render('auth/VerifyEmail', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::registerView(fn () => Inertia::render('auth/Register'));

        Fortify::twoFactorChallengeView(fn () => Inertia::render('auth/TwoFactorChallenge'));

        Fortify::confirmPasswordView(fn () => Inertia::render('auth/ConfirmPassword'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
