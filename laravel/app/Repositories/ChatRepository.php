<?php

declare(strict_types=1);

namespace App\Repositories;

class ChatRepository
{
    public function getUserChats()
    {
        return auth()->user()->chats()->with('users')->get();
    }
    public function sendMessage(): bool
    {
        return true;
    }
}
