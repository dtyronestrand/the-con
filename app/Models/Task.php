<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
class Task extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
       public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
    protected $fillable = [
        'name',
        'user_id',
        'calendar_id',
        'note_id',
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

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}