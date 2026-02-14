<?php

namespace App\Observers;

use App\Models\SyncQueue;
use Illuminate\Database\Eloquent\Model;

class SyncObserver
{
    public function saved(Model $model)
    {
        SyncQueue::create([
            'model_name' => get_class($model),
            'model_uuid' => $model->uuid,
            'payload' => $model->toArray(),
            'action' => $model->wasRecentlyCreated ? 'created' : 'updated',
        ]);
    }
}