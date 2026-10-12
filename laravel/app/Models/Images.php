<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'images';
    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'image',
    ];
}
