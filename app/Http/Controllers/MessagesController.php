<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\DeleteMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MinifiedMessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Throwable;

class MessagesController extends Controller
{
    // public function index()
    // {
    //     $users = User::all();
    //     return view('chat', ['users' => $users]);
    // }

    // public function getMessages()
    // {
    //     $messages = MinifiedMessageResource::collection(Message::query()->orderBy('created_at', 'desc')->get());
    //     return response()->json($messages);
    // }



    // public function store(StoreMessageRequest $request)
    // {
    //     $data = $request->validated();
    //     $data['user_id'] = auth()->user()->id;
    //     $message = Message::query()->create($data);
    //     broadcast(new MessageSent($message));
    //     $messageItem =  MinifiedMessageResource::collection($message);
    //     return response()->json($messageItem);
    // }

    public function store(StoreMessageRequest $request)
    {
        $chat = Chat::query()->where('id', $request->chat_id)->first();
        $messageData = [
            'body' => $request->body,
            'user_id' => auth()->user()->id,
            'chat_id' => $chat->id
        ];
        $message = Message::query()->create($messageData);

        broadcast(new MessageSent($chat, $message));
        $messageItem = new MinifiedMessageResource($message);
        return response()->json([$messageItem, $chat]);
    }

    public function update(UpdateMessageRequest $request)
    {
        $message = Message::query()->find($request->message_id);
        $message->update(['body' => $request->body]);
        return response()->json($message);
    }

    public function delete(DeleteMessageRequest $request)
    {
        $message = Message::query()->find($request->message_id);
        $message->delete();
        return response()->json(['status' => 'success']);
    }
}
