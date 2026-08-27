<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
class SavedLocation extends Model
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
    'lat',
    'lng',
    'grid_request_url',
];

}
