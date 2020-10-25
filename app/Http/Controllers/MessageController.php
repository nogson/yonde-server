<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::where('talk_id', $request->id)->orderBy('created_at', 'desc')->get();
        return ['data' => $messages];
    }

    public function store(Request $request)
    {
        $message = Message::create([
            'talk_id' => $request->talk_id,
            'message' => $request->message,
            'avatar_id' => $request->avatar_id,
        ]);

        return ['data' => $message];
    }
}
