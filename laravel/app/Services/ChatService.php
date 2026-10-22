<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\GetMessagesDTO;
use App\DTO\SendMessageDTO;
use App\Models\Chat;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class ChatService
{
    /**
     * @throws Throwable
     */
    public function sendMessage(SendMessageDTO $dto): bool
    {
        return DB::transaction(function () use ($dto) {
            $message = $dto->message;
            $old_message_id = $dto->old_message_id;
//            $files = $dto->files;
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
                $chat = Chat::query()->create();
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

    /**
     * @throws Throwable
     */
    public function getMessages(GetMessagesDTO $dto): Collection
    {
        $chat = auth()->user()->chats()
                     ->with([
                         'messages' => fn ($query) => $query
                         ->orderBy('messages.created_at'), 'messages.messageable'
                     ])
                     ->where('chats.id', $dto->chat_id)
                     ->where('active', true)
                     ->first();

        throw_if(!$chat, new Exception('Такого чата не существует', 404));

        return $chat->messages;
    }
}
