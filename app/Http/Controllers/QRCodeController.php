<?php

namespace App\Http\Controllers;

class QRCodeController extends Controller
{
    public function index(){
        return view('pesertaDidik.qrcode');
    }
}
