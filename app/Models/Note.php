<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Concerns\Syncable;
use Illuminate\Support\Str;

class Note extends Model
{
    use HasUuids, Syncable;

    protected $keyType = 'string';
    public $incrementing = false;
       public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'color',
        'tags',
        'pinned',
        'archived',
        'demoted_tasks',
        'width',
        'height',
    ];

    protected $casts = [
        'tags' => 'array',
        'pinned' => 'boolean',
        'archived' => 'boolean',
        'demoted_tasks' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('created_at');
    }
}
