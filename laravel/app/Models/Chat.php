<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';
    protected $fillable = [
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }
}
