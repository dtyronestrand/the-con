<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RemoteAuthService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('app.api_url'), '/');
    }

    public function login(string $email, string $password): bool
    {
        // Short timeout so a dead/unreachable remote server doesn't stall
        // callers that fall back to local behavior when offline (e.g. Fortify login).
        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/api/login", [
                'email' => $email,
                'password' => $password,
            ]);
        } catch (ConnectionException $e) {
            Log::error('Remote login failed: could not reach the server: '.$e->getMessage());

            return false;
        }

        if ($response->failed()) {
            Log::error('Remote login failed: '.$response->body());

            return false;
        }

        $this->storeTokens($response->json());

        return true;
    }

    public function getValidToken(): ?string
    {
        return AppSetting::where('key', 'api_token')->value('value');
    }

    /**
     * Sanctum tokens issued by the API don't expire (see the API's
     * config/sanctum.php), so a 401 means the token was revoked server-side —
     * there's no refresh token to fall back on, and no stored password to
     * silently re-authenticate with. The best we can do is drop the stale
     * token so the app knows to prompt the user to log in again.
     */
    public function refreshAfterUnauthorized(): ?string
    {
        $this->clearTokens();

        return null;
    }

    protected function storeTokens(array $data): void
    {
        AppSetting::updateOrCreate(['key' => 'api_token'], ['value' => $data['access_token'] ?? null]);
    }

    protected function clearTokens(): void
    {
        AppSetting::whereIn('key', ['api_token'])->delete();
    }
}
