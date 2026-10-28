<?php

namespace App\Http\Controllers;

use App\Support\DemoData;

class ChatController extends Controller
{
    public function index()
    {
        return view('chats', [
            'conversations' => DemoData::conversations(),
        ]);
    }
}
