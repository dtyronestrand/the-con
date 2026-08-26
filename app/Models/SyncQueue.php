<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SyncQueue extends Model
{
    protected $table = 'sync_queue';

    protected $fillable = [
        'model_name',
        'model_uuid',
        'payload',
        'action',
        'synced_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'synced_at' => 'datetime',
    ];


}
