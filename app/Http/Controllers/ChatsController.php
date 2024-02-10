<?php

namespace App\Http\Controllers;

use App\Events\ChatCreated;
use App\Http\Requests\CreateChatRequest;
use App\Http\Requests\ShowChatRequest;
use App\Http\Resources\MinifiedChatResource;
use App\Http\Resources\MinifiedMessageResource;
use App\Models\Chat;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function index()
    {
        if(!auth()->check()){
            return redirect()->route('index');
        }
        $chats = auth()->user()->chats->map(function (Chat $chat) {
            $users = $chat->users->map(function (User $user) {
                if ($user->id != auth()->user()->id) {
                    return $user;
                }
            });
            foreach ($users as $userItem) {
                if (!is_null($userItem)) {
                    $user = $userItem;
                }
            }
            return [
                'chat_id' => $chat->id,
                'chat_name' => $chat->name,
                'last_message' => $chat->messages->last(),
                'user' => $user,
            ];
        });
        return view('chat', ['chats' => $chats]);
    }
    public function store(CreateChatRequest $request)
    {
        try {
            $data = array(
                'name' => 'chat-' . $request->user_id . auth()->user()->id
            );
            $chat = Chat::query()->create($data);
            $chat->users()->attach($request->user_id);
            $chat->users()->attach(auth()->user()->id);
            return response()->json($chat);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function show($chatName, Request $request)
    {
        $chat = Chat::query()->where('name', $chatName)->first();
        $messagesHistory = $chat->messages;
        if (!empty($messagesHistory)) {
            $users = $chat->users->map(function (User $user) {
                if ($user->id != auth()->user()->id) {
                    return $user;
                }
                return;
            });
            foreach ($users as $userItem) {
                if (!is_null($userItem)) {
                    $user = $userItem;
                }
            }
        }

        $messages = MinifiedMessageResource::collection($messagesHistory);
        return response()->json([$user, $chat, $messages]);
    }
}
