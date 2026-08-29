<?php

use App\Models\AppSetting;
use Illuminate\Support\Facades\Http;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('a first pull (no cached last_pull_timestamp) omits `since` entirely instead of defaulting to a lookback window', function () {
    AppSetting::create(['key' => 'api_token', 'value' => 'test-token']);
    cache()->forget('last_pull_timestamp');

    Http::fake(['*/api/services/pull*' => Http::response(['changes' => [], 'timestamp' => now()->toDateTimeString()], 200)]);

    $this->artisan('sync:pull')->assertExitCode(0);

    Http::assertSent(function ($request) {
        // A defaulted-to-"yesterday" `since` would make a freshly
        // reinstalled device's first scheduled pull silently miss any
        // remote data older than that window.
        return ! array_key_exists('since', $request->data());
    });
});

test('a later pull sends the cached last_pull_timestamp as `since`', function () {
    AppSetting::create(['key' => 'api_token', 'value' => 'test-token']);
    cache()->put('last_pull_timestamp', '2026-01-01 00:00:00');

    Http::fake(['*/api/services/pull*' => Http::response(['changes' => [], 'timestamp' => now()->toDateTimeString()], 200)]);

    $this->artisan('sync:pull')->assertExitCode(0);

    Http::assertSent(fn ($request) => ($request->data()['since'] ?? null) === '2026-01-01 00:00:00');
});
