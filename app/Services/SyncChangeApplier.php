<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Note;
use App\Models\Service;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SyncChangeApplier
{
    /**
     * Foreign keys are just as local-DB-specific as the primary key, so each
     * travels over the wire as `<relation>_uuid` and gets resolved to the
     * model's real local FK column here rather than being forceFilled as-is
     * (there is no `category_uuid`/`note_uuid` column locally).
     */
    protected const RELATION_UUID_FIELDS = [
        Service::class => ['category_uuid' => ['model' => Category::class, 'column' => 'category_id']],
        Task::class => ['note_uuid' => ['model' => Note::class, 'column' => 'note_id']],
    ];

    /**
     * The server never sends a `user_id`/`user_uuid` for per-user records —
     * it's already implicit in which account pulled them, and the client and
     * server have entirely separate, unrelated `users` tables anyway. This
     * app is single-user-per-device, so pulled rows are simply attributed to
     * this device's own local user.
     */
    protected const USER_SCOPED_MODELS = [Note::class, Task::class];

    /**
     * Column lists per table, cached for the life of one apply() call — the
     * wire payload carries fields (like `deleted_at` on models with no local
     * soft-delete column) that don't exist on every local table, and would
     * otherwise break the insert/update.
     */
    protected array $columnCache = [];

    /**
     * Apply a `{table_name: [rows]}` changes payload, as returned by
     * GET /api/services/pull, to the local database. Rows are matched by
     * the wire field `uuid`, which is the model's own `id` — `id` is a uuid
     * primary key now, shared as-is between local and remote.
     */
    public function apply(array $changes): void
    {
        DB::transaction(function () use ($changes) {
            foreach ($changes as $table => $rows) {
                $modelClass = $this->modelClassForTable($table);

                if (! class_exists($modelClass)) {
                    continue;
                }

                foreach ($rows as $row) {
                    $this->applyRow($modelClass, $row);
                }
            }
        });
    }

    protected function applyRow(string $modelClass, array $row): void
    {
        if (empty($row['uuid'])) {
            return;
        }

        $uuid = $row['uuid'];

        foreach (static::RELATION_UUID_FIELDS[$modelClass] ?? [] as $wireKey => $map) {
            if (array_key_exists($wireKey, $row)) {
                $row[$map['column']] = $row[$wireKey] === null
                    ? null
                    : $map['model']::where('id', $row[$wireKey])->value('id');
            }

            unset($row[$wireKey]);
        }

        if (! empty($row['deleted_at'])) {
            $modelClass::where('id', $uuid)->delete();

            return;
        }

        // `uuid` is the wire field name only — locally it's the model's own
        // `id` — so it must be dropped rather than forceFilled as-is (there's
        // no `uuid` column) after being used to set the real `id`.
        unset($row['uuid']);
        $row['id'] = $uuid;

        if (in_array($modelClass, static::USER_SCOPED_MODELS, true)) {
            // Auth::id() covers the Inertia-triggered sync; the scheduled
            // sync:pull command runs outside any web session, so it falls
            // back to the device's one local user account.
            $row['user_id'] = Auth::id() ?? User::query()->value('id');
        }

        $record = $modelClass::firstOrNew(['id' => $uuid]);
        $row = array_intersect_key($row, array_flip($this->columnsFor($record->getTable())));
        $record->forceFill($row);

        // saveQuietly() suppresses the saved/created events, so applying a
        // pulled change never re-triggers SyncObserver and echoes it straight
        // back to the server as an outgoing push.
        $record->saveQuietly();
    }

    protected function columnsFor(string $table): array
    {
        return $this->columnCache[$table] ??= Schema::getColumnListing($table);
    }

    protected function modelClassForTable(string $table): string
    {
        return 'App\\Models\\'.Str::studly(Str::singular($table));
    }
}
