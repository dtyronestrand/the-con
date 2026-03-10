<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Task extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'calendar_id',
        'notes',
        'done',
        'due_date',
        'sub_tasks',
        'attachments',
    ];

    protected $casts =[
        'done' => 'boolean',
        'due_date' => 'date',
        'sub_tasks' => 'json',
        'attachments' => 'json',
    ];
    /**
     * Get the user that owns the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }




}