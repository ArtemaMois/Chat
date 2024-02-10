<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\SearchUserRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Avatar;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function index()
    {
        if (auth()->user()) {
            $chats = auth()->user()->chats->map(function ($chat) {
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
                    'username' => $user->name,
                ];
            });
            return view('chat', ['chats' => $chats]);
        }
        return view('register');
    }

    public function store(AccountStoreRequest $request)
    {
        $user = User::query()->create($request->validated());
        return redirect()->route('signInForm');
    }

    public function signInForm()
    {
        return view('login');
    }

    public function signIn(SignInRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return redirect()->back();
        }
        return redirect()->route('chat.index');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('signInForm');
    }

    public function update(UpdateAccountRequest $request)
    {
        $user = User::query()->find(auth()->user()->id);
        try {
            $user->update($request->validated());
            return response()->json($user);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function searchUser(SearchUserRequest $request)
    {
        $user = User::query()->where('name', 'LIKE', "%{$request->username}%")->get();
        return response()->json($user);
    }

    public function accountForm()
    {
        $avatar = Avatar::query()->where('user_id', auth()->user()->id)->where('is_main', true)->first();
        $images = auth()->user()->avatars->reverse();
        return view('account', ['user' => auth()->user(), 'images' => $images, 'avatar' => $avatar]);
    }

    public function storeImage(StoreImageRequest $request)
    {
        $filenameWithExt = $request->file('image')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extention = $request->file('image')->getClientOriginalExtension();
        $path = $request->file('image')->store('/avatars');
        // dd($path);
        if($request->is_main == 1){
            $avatar = Avatar::query()->where('user_id', auth()->user()->id)->where('is_main', true)->first();
            if($avatar){
                $avatar->update(['is_main' => false]);
            }
            Avatar::query()->create(['user_id' => auth()->user()->id,
            'path' => $path,
            'is_main' => true]);
        } else{
            Avatar::query()->create(['user_id' => auth()->user()->id,
            'path' => $path,
            'is_main' => false]);
        }
        return redirect()->route('account.update.form');
    }
}
