<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = [
        'chat_id',
        'user_id',
        'text',
        'attachments',
        'old_message_id'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function images()
    {
        return $this->morphMany(Images::class, 'imageable');
    }

    public function files()
    {
        return $this->morphMany(File::class , 'fileable');
    }
}
