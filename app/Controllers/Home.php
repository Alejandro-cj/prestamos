<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function __construct()
    {
        helper(['form']);
    }
    public function index()
    {
        return view('index');
    }

    public function plantila() {
        return view('plantilla'); 
    }
}
