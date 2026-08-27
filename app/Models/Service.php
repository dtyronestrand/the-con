<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
class Service extends Model
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
    'url',
    'icon',
    'category_id',
 ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
