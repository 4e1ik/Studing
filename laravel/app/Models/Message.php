<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = [
        'chat_id',
        'receiver_id',
        'messageable_id',
        'messageable_type',
        'text',
        'is_read',
        'old_message_id'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function files(): MorphMany
    {
        return $this->morphMany(File::class , 'fileable');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function messageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
