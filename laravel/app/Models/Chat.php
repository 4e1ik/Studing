<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'user_chatable', 'user_chat');
    }
}
