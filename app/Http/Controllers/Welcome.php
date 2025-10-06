<?php

namespace App\Http\Controllers;

class Welcome extends Controller
{
    public function welcome() {
        return view("welcome");
    }
}
