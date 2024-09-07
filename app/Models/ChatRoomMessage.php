<?php

namespace App\Models;

use App\Enums\SenderTypeEnum;
use App\Observers\ChatObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ChatObserver::class])]
class ChatRoomMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_room_id',
        'sender_type',
        'message',
    ];

    protected $casts = [
        'sender_type' => SenderTypeEnum::class
    ];

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }
}
