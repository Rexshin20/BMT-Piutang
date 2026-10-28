<?php

namespace App\Http\Controllers;

use App\Support\DemoData;

class ReceivableController extends Controller
{
    public function index()
    {
        return view('piutang', [
            'receivables' => DemoData::receivables(),
        ]);
    }
}
