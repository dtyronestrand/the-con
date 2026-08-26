<?php

namespace App\Services;

use App\Models\AppSetting;
use Carbon\Carbon;
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
        $response = Http::timeout(5)->post("{$this->baseUrl}/api/login", [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->failed()) {
            Log::error('Remote login failed: '.$response->body());

            return false;
        }

        $this->storeTokens($response->json());

        return true;
    }

    /**
     * Returns a token known to be valid, refreshing first if it's missing or expired.
     * Returns null if there's no way to get a valid token (no refresh token, or refresh failed).
     */
    public function getValidToken(): ?string
    {
        $token = AppSetting::where('key', 'api_token')->value('value');
        $expiresAt = AppSetting::where('key', 'api_token_expires_at')->value('value');

        $expired = ! $token || ! $expiresAt || Carbon::parse($expiresAt)->isPast();

        if (! $expired) {
            return $token;
        }

        return $this->refresh() ? AppSetting::where('key', 'api_token')->value('value') : null;
    }

    /**
     * Call after a request comes back 401 despite getValidToken() returning a token
     * (the server may have revoked it early). Refreshes once and returns the new token,
     * or null if the refresh token is no longer valid either.
     */
    public function refreshAfterUnauthorized(): ?string
    {
        return $this->refresh() ? AppSetting::where('key', 'api_token')->value('value') : null;
    }

    protected function refresh(): bool
    {
        $refreshToken = AppSetting::where('key', 'api_refresh_token')->value('value');

        if (! $refreshToken) {
            return false;
        }

        $response = Http::post("{$this->baseUrl}/api/token/refresh", [
            'refresh_token' => $refreshToken,
        ]);

        if ($response->failed()) {
            Log::error('Remote token refresh failed: '.$response->body());
            $this->clearTokens();

            return false;
        }

        $this->storeTokens($response->json());

        return true;
    }

    protected function storeTokens(array $data): void
    {
        AppSetting::updateOrCreate(['key' => 'api_token'], ['value' => $data['access_token'] ?? null]);
        AppSetting::updateOrCreate(['key' => 'api_refresh_token'], ['value' => $data['refresh_token'] ?? null]);

        $expiresAt = isset($data['expires_in'])
            ? now()->addSeconds((int) $data['expires_in'])
            : now()->addDay();

        AppSetting::updateOrCreate(['key' => 'api_token_expires_at'], ['value' => $expiresAt->toDateTimeString()]);
    }

    protected function clearTokens(): void
    {
        AppSetting::whereIn('key', ['api_token', 'api_refresh_token', 'api_token_expires_at'])->delete();
    }
}
