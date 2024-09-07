<?php

namespace App\Observers;

use App\Enums\SenderTypeEnum;
use App\Models\ChatRoomMessage;
use LucianoTonet\GroqPHP\Groq;

class ChatObserver
{
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    /**
     * Handle the ChatRoomMessage "created" event.
     */
    public function created(ChatRoomMessage $chatRoomMessage): void
    {
        $this->database->getReference('rooms/' . $chatRoomMessage->chat_room_id . '/'. $chatRoomMessage->id)
            ->set([
                'id' => $chatRoomMessage->id,
                'chat_room_id' => $chatRoomMessage->chat_room_id,
                'sender_type' => $chatRoomMessage->sender_type,
                'message' => $chatRoomMessage->message,
                'created_at' => $chatRoomMessage->created_at
            ]);

        if ($chatRoomMessage->sender_type == SenderTypeEnum::USER) {
            $groq = new Groq();
            $response = $groq->chat()->completions()->create([
                'model' => 'gemma-7b-it',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Anda adalah psikolog yang berpengalaman dan bertugas untuk menjadi teman bicara. Gunakan bahasa kasual dan mudah dimengerti anak muda. jawab dengan singkat dan jelas",
                    ],
                    [
                        'role' => 'user',
                        'content' => $chatRoomMessage->message
                    ]
                ],
            ]);

            ChatRoomMessage::create([
                'chat_room_id' => $chatRoomMessage->chat_room_id,
                'message' => $response['choices'][0]['message']['content'],
                'sender_type' => SenderTypeEnum::BOT->value
            ]);
        }
    }

    /**
     * Handle the ChatRoomMessage "updated" event.
     */
    public function updated(ChatRoomMessage $chatRoomMessage): void
    {
        //
    }

    /**
     * Handle the ChatRoomMessage "deleted" event.
     */
    public function deleted(ChatRoomMessage $chatRoomMessage): void
    {
        //
    }

    /**
     * Handle the ChatRoomMessage "restored" event.
     */
    public function restored(ChatRoomMessage $chatRoomMessage): void
    {
        //
    }

    /**
     * Handle the ChatRoomMessage "force deleted" event.
     */
    public function forceDeleted(ChatRoomMessage $chatRoomMessage): void
    {
        //
    }
}
