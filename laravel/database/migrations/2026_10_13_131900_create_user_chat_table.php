<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats');
            $table->morphs('user_chatable');
            $table->unique(['chat_id', 'user_chatable_type', 'user_chatable_id']); // Запрещает делать 2 одинаковые записи с одним чатом и одним пользователем
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_chat');
    }
};
