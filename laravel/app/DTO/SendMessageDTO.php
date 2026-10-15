<?php

declare(strict_types=1);

namespace App\DTO;

class SendMessageDTO
{
    public function __construct(
        public ?string  $message = null,
        public ?int     $old_message_id = null,
        public ?array   $files = null,
    ){}
}
