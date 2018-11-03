<?php

namespace App\Http\Controllers;

use App\Chatty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Pusher\Laravel\Facades\Pusher;
 
class ChattyController extends Controller
{
    public function getUserConvById(Request $request)
    {
        $user_id = $request->id;
        $auth_user = Auth::id();
        $chat = Chatty::whereIn('sender_id', [$auth_user, $user_id])
                        ->whereIn('receiver_id', [$auth_user, $user_id])
                        ->orderBy('created_at', 'DESC')->get();
        return $chat;
    }

    public function saveUserChat(Request $request, $id)
    {
        // return $request->all();
        $sender_id = Auth::id();
        $receiver_id = $id;
        $chat = $request->chat;
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'chat' => $chat,
            'read' => 1
        ];
        $chat = Chatty::create($data);
        $finalData = Chatty::where('id', $chat->id)->first();
        Pusher::trigger('chat_channel', 'chat_save', ['message' => $finalData]);
        return $finalData;
    }
}
