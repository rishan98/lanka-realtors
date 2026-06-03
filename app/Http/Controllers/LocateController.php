<?php

namespace App\Http\Controllers;

class LocateController extends Controller
{
    public function __invoke()
    {
        return view('pages.locate');
    }
}
