<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function intro(): View
    {
        return view('sidebar.intro');
    }

    public function contact(): string
    {
        return 'contact';
    }

    public function terms(): string
    {
        return 'terms';
    }

    public function about(): string
    {
        return 'about';
    }
}
