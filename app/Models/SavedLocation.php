<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class SavedLocation extends Model
{
    use HasUuids;
protected $fillable = [
    'name',
    'lat',
    'lng',
    'grid_request_url',
];
    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
