<?php

use App\Jobs\PushToRemote;
use App\Models\AppSetting;
use App\Models\Category;
use App\Models\SyncQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    AppSetting::create(['key' => 'api_token', 'value' => 'test-token']);
});

test('saving an observed model queues its table name, not its class name', function () {
    SyncQueue::truncate();

    Category::create(['name' => 'Queued Category']);

    $row = SyncQueue::first();
    expect($row->model_name)->toBe('categories');
    expect($row->action)->toBe('created');
});

test('pushing sends the table under the `table` wire key the API expects', function () {
    Http::fake(['*/api/services/push' => Http::response(['message' => 'Synced.'], 200)]);

    SyncQueue::create([
        'model_name' => 'categories',
        'model_uuid' => (string) Str::uuid(),
        'payload' => ['name' => 'x'],
        'action' => 'created',
    ]);

    (new PushToRemote)->handle(app(App\Services\RemoteAuthService::class));

    Http::assertSent(fn ($request) => $request['table'] === 'categories' && ! isset($request['model_name']));
});

test('one failed row does not block the rest of the batch from syncing', function () {
    $badUuid = (string) Str::uuid();
    $goodUuid = (string) Str::uuid();

    Http::fake([
        '*/api/services/push' => function ($request) use ($badUuid) {
            return $request['model_uuid'] === $badUuid
                ? Http::response(['message' => 'nope'], 422)
                : Http::response(['message' => 'Synced.'], 200);
        },
    ]);

    SyncQueue::create(['model_name' => 'categories', 'model_uuid' => $badUuid, 'payload' => ['name' => 'bad'], 'action' => 'created']);
    SyncQueue::create(['model_name' => 'categories', 'model_uuid' => $goodUuid, 'payload' => ['name' => 'good'], 'action' => 'created']);

    (new PushToRemote)->handle(app(App\Services\RemoteAuthService::class));

    expect(SyncQueue::where('model_uuid', $badUuid)->value('synced_at'))->toBeNull();
    expect(SyncQueue::where('model_uuid', $goodUuid)->value('synced_at'))->not->toBeNull();
});
