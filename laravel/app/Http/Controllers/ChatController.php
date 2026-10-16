<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\SendMessageDTO;
use App\Heplers\ApiResponse;
use App\Http\Requests\SendMessageRequest;
use App\Services\ChatService;
use Throwable;

class ChatController extends Controller
{
    public function sendMessage(SendMessageRequest $request, ChatService $chatService)
    {
        try {
            return ApiResponse::success($chatService->sendMessage(
                new SendMessageDTO(
                    chat_id:        $request['chat_id'],
                    receiver_id:    $request['receiver_id'],
                    message:        $request['message'],
                    old_message_id: $request['old_message_id'],
                    files:          $request->file('files'),
                )
            ));
        } catch (Throwable $exception) {
            return ApiResponse::error($exception);
        }
    }
}
