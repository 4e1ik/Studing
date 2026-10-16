<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\SendMessageDTO;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function sendMessage(SendMessageDTO $dto): bool
    {
        return DB::transaction(function () use ($dto) {
            $message = $dto->message;
            $old_message_id = $dto->old_message_id;
            $files = $dto->files;
            $chat_id = $dto->chat_id;
            $receiver_id = $dto->receiver_id;

            $user = auth()->user();

            $chat = ($chat_id === null) ?
                $user->chats()
                    ->whereHas('users', fn ($query) =>
                        $query->where('users.id', $receiver_id)
                    )->first()
                : $user->chats()->find($chat_id);

            if ($chat === null && $chat_id === null) {
                $chat = Chat::create();
                $chat->users()->attach([$user->id, $receiver_id]);
            }

            $user->messages()->create([
                'chat_id' => $chat->id,
                'receiver_id' => $receiver_id,
                'old_message_id' => $old_message_id,
                'text' => $message,
            ]);

            return true;
        });
    }
}
