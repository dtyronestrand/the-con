<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str; 
use Inertia\Testing\Concerns\Has;

class Category extends Model
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
        'id',
        'name',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
