<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = [
        'fileable_id',
        'fileable_type',
        'file',
        'type',
        'extension'
    ];
}
