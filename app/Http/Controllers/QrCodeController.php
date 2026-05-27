<?php

namespace App\Http\Controllers;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('qr_generator.index');
    }
}