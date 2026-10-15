<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\SendMessageDTO;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function sendMessage(SendMessageDTO $dto): bool
    {
        return DB::transaction(function () use ($dto) {
            $message = $dto->message;
            $old_message_id = $dto->old_message_id;
            $files = $dto->files;
            $user = auth()->user();

            dd($user);
        });
    }
}
