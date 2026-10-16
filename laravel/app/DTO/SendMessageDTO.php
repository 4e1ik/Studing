<?php

declare(strict_types=1);

namespace App\DTO;

class SendMessageDTO
{
    public function __construct(
        public ?int     $chat_id = null,
        public int      $receiver_id,
        public ?string  $message = null,
        public ?int     $old_message_id = null,
        public ?array   $files = null,
    ){}
}
