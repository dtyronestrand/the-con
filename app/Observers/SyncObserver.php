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
            'model_name' => get_class($model),
            'model_uuid' => $model->uuid,
            'payload' => $model->toArray(),
            'action' => $action,
        ]);
    }
}