<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncChangeApplier
{
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

                if (!class_exists($modelClass)) {
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

        // Foreign keys are just as local-DB-specific as the primary key, so they
        // travel as a `<relation>_uuid` and get resolved to a local id here.
        if ($modelClass === Service::class && isset($row['category_uuid'])) {
            $row['category_id'] = Category::where('id', $row['category_uuid'])->value('id');
        }

        if (!empty($row['deleted_at'])) {
            $modelClass::where('id', $row['uuid'])->delete();

            return;
        }

        // `uuid` is the wire field name; locally it's the model's own `id`,
        // and it must be kept (not stripped) so the record lands under the
        // same id both locally and remotely.
        $row['id'] = $row['uuid'];

        $record = $modelClass::firstOrNew(['id' => $row['uuid']]);
        $record->forceFill($row);

        // saveQuietly() suppresses the saved/created events, so applying a
        // pulled change never re-triggers SyncObserver and echoes it straight
        // back to the server as an outgoing push.
        $record->saveQuietly();
    }

    protected function modelClassForTable(string $table): string
    {
        return 'App\\Models\\'.Str::studly(Str::singular($table));
    }
}
