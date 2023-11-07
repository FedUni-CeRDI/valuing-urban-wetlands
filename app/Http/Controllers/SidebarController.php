<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class SidebarController extends Controller
{
    public function intro(): View
    {
        return view('sidebar.intro');
    }

    public function contact(): string
    {
        return view('sidebar.contact');
    }

    public function terms(): string
    {
        return view('sidebar.terms');
    }

    public function about(): string
    {
        return view('sidebar.about');
    }
}
