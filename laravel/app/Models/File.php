<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
