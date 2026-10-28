<?php

namespace App\Http\Controllers;

use App\Support\DemoData;

class AppController extends Controller
{
    public function index()
    {
        return view('apps', [
            'apps' => DemoData::apps(),
        ]);
    }
}
