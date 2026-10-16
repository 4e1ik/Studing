<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = [
        'chat_id',
        'receiver_id',
        'messageable_id',
        'messageable_type',
        'text',
        'is_read',
        'old_message_id'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function files()
    {
        return $this->morphMany(File::class , 'fileable');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function messageable()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
