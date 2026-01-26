<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedLocation extends Model
{
protected $fillable = [
    'name',
    'lat',
    'lng',
    'grid_request_url',
];
}
