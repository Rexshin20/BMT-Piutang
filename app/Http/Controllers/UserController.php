<?php

namespace App\Http\Controllers;

use App\Support\DemoData;

class UserController extends Controller
{
    public function index()
    {
        return view('users', [
            'users' => DemoData::users(),
        ]);
    }
}
