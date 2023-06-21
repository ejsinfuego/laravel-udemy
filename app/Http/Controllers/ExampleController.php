<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage() {
        return view('homepage');
    }


    public function aboutPage() {

        return view('single-post');
    }
}

