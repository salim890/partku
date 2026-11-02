<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function tentang()
    {
        return view('tentang');
    }

    public function kontak()
    {
        return view('kontak');
    }

    public function whatsapp()
    {
        return view('whatsapp');
    }
    public function kebijakan()
    {
        return view('kebijakan');
    }
}