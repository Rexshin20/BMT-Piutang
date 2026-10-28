<?php

namespace App\Http\Controllers;

use App\Support\DemoData;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks', [
            'tasks' => DemoData::tasks(),
        ]);
    }
}
