<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;
class SavedLocation extends Model
{
    use HasUuid;
protected $fillable = [
    'name',
    'lat',
    'lng',
    'grid_request_url',
];

}
