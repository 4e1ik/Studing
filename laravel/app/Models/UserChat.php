<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserChat extends Model
{
    protected $table = 'user_chat';
    protected $fillable = [
        'user_id',
        'chat_id'
    ];
}
