<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;
use App\Models\Concerns\Syncable;

class StickyNote extends Model
{
    use HasUuid, Syncable;

    protected $fillable = [
        'user_id',
        'content',
        'color',
        'width',
        'height',
    ];
}
