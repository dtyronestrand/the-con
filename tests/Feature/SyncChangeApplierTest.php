<?php

use App\Models\Category;
use App\Models\Note;
use App\Models\Service;
use App\Models\Task;
use App\Models\User;
use App\Services\SyncChangeApplier;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function syncApplier(): SyncChangeApplier
{
    return app(SyncChangeApplier::class);
}

test('applying a service with a category_uuid resolves it to the local category_id', function () {
    $categoryUuid = (string) Str::uuid();
    $serviceUuid = (string) Str::uuid();

    syncApplier()->apply([
        'categories' => [['uuid' => $categoryUuid, 'name' => 'Cat', 'deleted_at' => null]],
        'services' => [[
            'uuid' => $serviceUuid, 'name' => 'Svc', 'url' => null, 'icon' => null,
            'category_uuid' => $categoryUuid, 'deleted_at' => null,
        ]],
    ]);

    // This exact shape used to crash outright: forceFill() would try to
    // write the wire-only `uuid`/`category_uuid` keys as real columns.
    $service = Service::find($serviceUuid);
    expect($service)->not->toBeNull();
    expect($service->category_id)->toBe($categoryUuid);
});

test('a wire field the local table has no column for is silently dropped rather than crashing the insert', function () {
    $uuid = (string) Str::uuid();

    // Category has no local deleted_at column, but the server always sends
    // one (null for a live record) — this used to break the insert.
    syncApplier()->apply([
        'categories' => [['uuid' => $uuid, 'name' => 'No crash please', 'deleted_at' => null]],
    ]);

    expect(Category::find($uuid)?->name)->toBe('No crash please');
});

test('a tombstone (non-null deleted_at) deletes the record locally instead of saving it', function () {
    $uuid = (string) Str::uuid();
    Category::create(['id' => $uuid, 'name' => 'Will be deleted']);

    syncApplier()->apply([
        'categories' => [['uuid' => $uuid, 'name' => 'Will be deleted', 'deleted_at' => '2026-01-01 00:00:00']],
    ]);

    expect(Category::find($uuid))->toBeNull();
});

test('a pulled note is attributed to the currently authenticated local user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $uuid = (string) Str::uuid();

    syncApplier()->apply([
        'notes' => [[
            'uuid' => $uuid, 'title' => 't', 'content' => 'c', 'color' => '#fff',
            'width' => '200px', 'height' => '200px', 'tags' => [], 'pinned' => false,
            'archived' => false, 'demoted_tasks' => [], 'deleted_at' => null,
        ]],
    ]);

    expect(Note::find($uuid)?->user_id)->toBe($user->id);
});

test('a pulled note falls back to the sole local user when there is no authenticated session', function () {
    // The scheduled `sync:pull` command runs outside any web session, so
    // there is no Auth::id() to attribute pulled notes/tasks to.
    $user = User::first() ?? User::factory()->create();

    $uuid = (string) Str::uuid();

    syncApplier()->apply([
        'notes' => [[
            'uuid' => $uuid, 'title' => 't', 'content' => 'c', 'color' => '#fff',
            'width' => '200px', 'height' => '200px', 'tags' => [], 'pinned' => false,
            'archived' => false, 'demoted_tasks' => [], 'deleted_at' => null,
        ]],
    ]);

    expect(Note::find($uuid)?->user_id)->toBe($user->id);
});

test('a pulled task with a note_uuid resolves it to the local note_id', function () {
    User::factory()->create();

    $noteUuid = (string) Str::uuid();
    $taskUuid = (string) Str::uuid();

    syncApplier()->apply([
        'notes' => [[
            'uuid' => $noteUuid, 'title' => 't', 'content' => 'c', 'color' => '#fff',
            'width' => '200px', 'height' => '200px', 'tags' => [], 'pinned' => false,
            'archived' => false, 'demoted_tasks' => [], 'deleted_at' => null,
        ]],
        'tasks' => [[
            'uuid' => $taskUuid, 'name' => 'Task', 'note_uuid' => $noteUuid,
            'calendar_id' => null, 'notes' => null, 'done' => false, 'due_date' => null,
            'sub_tasks' => [], 'attachments' => [], 'deleted_at' => null,
        ]],
    ]);

    expect(Task::find($taskUuid)?->note_id)->toBe($noteUuid);
});

test('applying a change never re-queues it as an outgoing push', function () {
    $uuid = (string) Str::uuid();

    syncApplier()->apply([
        'categories' => [['uuid' => $uuid, 'name' => 'Pulled', 'deleted_at' => null]],
    ]);

    expect(App\Models\SyncQueue::where('model_uuid', $uuid)->exists())->toBeFalse();
});
