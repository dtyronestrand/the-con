<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\HasUuid;

class Service extends Model
{
    use HasUuid;
 protected $fillable = [
    'uuid',
    'name',
    'url',
    'icon',
    'category_id',
 ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
