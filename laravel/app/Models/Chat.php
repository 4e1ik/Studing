<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';
    protected $fillable = [
        'chatable_id',
        'chatable_type',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];


    public function message()
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }
}
