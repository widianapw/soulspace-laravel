<?php

namespace App\Http\Controllers\Api;

use App\Enums\SenderTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Http\Resources\GeneralResource;
use Illuminate\Http\Request;

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
        $messages = \App\Models\ChatRoomMessage::where('chat_room_id', $chatroomId)->get();
        return GeneralResource::collection($messages);
    }

    public function postChat(ChatRequest $request)
    {
        $chatroomId = auth()->user()->chatRoom->id;
        $message = \App\Models\ChatRoomMessage::create([
            'chat_room_id' => $chatroomId,
            'message' => $request->message,
            'sender_type' => SenderTypeEnum::USER->value
        ]);

        return new GeneralResource($message);
    }
}
