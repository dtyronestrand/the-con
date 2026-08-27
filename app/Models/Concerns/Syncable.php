<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\PushToRemote;

trait Syncable
{
    /**
     * Transient loop-prevention flag — never persisted, so it must stay a real
     * PHP property rather than going through Eloquent's attribute system
     * (which would try to save it as a DB column and fail).
     */
    public bool $is_syncing = false;

    public static function bootSyncable()
    {
        if (app()->environment('native')){
            static::saved(function (Model $model) 
            {
                if(!$model->is_syncing) {
                    dispatch(new PushToRemote($model, 'savee'));
                }
            });
            static::deleted(function (Model $model) 
            {
                if(!$model->is_syncing) {
                    dispatch(new PushToRemote($model, 'delete'));
                }
            });
        }
    }
    public function saveQuietlyIsSyncing()
    {
        $this->is_syncing = true;
        $this->save();
        $this->is_syncing = false;
    }
}