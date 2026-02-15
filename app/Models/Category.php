<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\HasUuid;
use Inertia\Testing\Concerns\Has;

class Category extends Model
{
    use HasUuid;
    protected $fillable = [
        'uuid',
        'name',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
