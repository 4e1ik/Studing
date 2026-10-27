<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\GetMessagesDTO;
use App\DTO\SendMessageDTO;
use App\Events\MessageSent;
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
        $message = $dto->message;
        $oldMessageId = $dto->old_message_id;
//            $files = $dto->files;
        $chatId = $dto->chat_id;
        $receiverId = $dto->receiver_id;

        $user = auth()->user();

        $chat = ($chatId === null) ?
            $user->chats()
                ->whereHas('users', fn ($query) =>
                    $query->where('users.id', $receiverId)
                )->first()
            : $user->chats()->where('chats.id', $chatId)->first();

        throw_if($chat === null && $chatId !== null, new Exception('Чат не найден', 404));

        $message = DB::transaction(function () use ($message, $oldMessageId, $chatId, $receiverId, $chat, $user) {
            if ($chat === null && $chatId === null) {
                $chat = Chat::query()->create();
                $chat->users()->attach([$user->id, $receiverId]); //Добавляем в линковочную таблицу связь чата с пользователями
            }

            return $user->messages()->create([
                'chat_id' => $chat->id,
                'receiver_id' => $receiverId,
                'old_message_id' => $oldMessageId,
                'text' => $message,
            ]);
        });

        MessageSent::dispatch(message: $message);

        return true;
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
