<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['name'];

    protected $keyType = 'string';

    public $incrementing = false;

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'note_tag', 'tag_id', 'note_id');
    }
}
