<?php

namespace App\Http\Controllers;

use App\Models\Configuration\NameSystem\NameSystem;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $image = NameSystem::first();

        return view('home', compact('image'));
    }
}
