<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Service extends Model
{
 protected $fillable = [
    'id',
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
