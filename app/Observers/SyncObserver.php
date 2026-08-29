<?php

namespace App\Observers;

use App\Models\SyncQueue;
use Illuminate\Database\Eloquent\Model;

class SyncObserver
{
    public function saved(Model $model)
    {
        $this->queue($model, $model->wasRecentlyCreated ? 'created' : 'updated');
    }

    public function deleted(Model $model)
    {
        $this->queue($model, 'deleted');
    }

    protected function queue(Model $model, string $action): void
    {
        SyncQueue::create([
            // The client and server are separate Laravel apps; a table name
            // is a stable identifier to send over the wire, whereas the
            // model's FQCN only happens to match today because both apps use
            // the same namespace.
            'model_name' => $model->getTable(),
            'model_uuid' => $model->id,
            'payload' => $model->toArray(),
            'action' => $action,
        ]);
    }
}
