<?php

namespace App\Http\Controllers;

use App\Models\Piutang;

class ReceivableController extends Controller
{
    public function index()
    {
        return view('piutang', [
            'receivables' => Piutang::query()->with('peminjam')->orderByDesc('tanggal')->orderByDesc('id')->get(),
        ]);
    }
}
