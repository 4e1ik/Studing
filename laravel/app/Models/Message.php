<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = [
        'chat_id',
        'messageable_id',
        'messageable_type',
        'text',
        'old_message_id'
    ];

    public function files()
    {
        return $this->morphMany(File::class , 'fileable');
    }
}
