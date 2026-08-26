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
     * `uuid`, not `id` — local and remote autoincrement IDs are independent
     * once there are two databases, so `id` can't be trusted as identity.
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
            $row['category_id'] = Category::where('uuid', $row['category_uuid'])->value('id');
        }

        if (!empty($row['deleted_at'])) {
            $modelClass::where('uuid', $row['uuid'])->delete();

            return;
        }

        // The remote server's own `id` is meaningless locally now that `uuid`
        // is the identity column — applying it would clobber (or collide with)
        // the local autoincrement primary key.
        unset($row['id']);

        $record = $modelClass::firstOrNew(['uuid' => $row['uuid']]);
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
