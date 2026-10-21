<?php

declare(strict_types=1);

namespace App\DTO;

class GetMessagesDTO
{
    public function __construct(
        public int     $chat_id,
//        public int      $receiver_id,
    ){}
}
