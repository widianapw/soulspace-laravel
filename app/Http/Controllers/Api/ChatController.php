<?php

namespace App\Http\Controllers\Api;

use App\Enums\SenderTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use Illuminate\Http\Request;
use LucianoTonet\GroqPHP\Groq;

class ChatController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    public function get(Request $request)
    {
        $chatroomId = auth()->user()->chatRoom->id;
        $messages = \App\Models\ChatRoomMessage::where('chat_room_id', $chatroomId)->latest()->get();
        return $messages;
    }

    public function postChat(ChatRequest $request)
    {
        $chatroomId = auth()->user()->chatRoom->id;
        $message = \App\Models\ChatRoomMessage::create([
            'chat_room_id' => $chatroomId,
            'message' => $request->message,
            'sender_type' => SenderTypeEnum::USER->value
        ]);

        return $message;
    }
}
