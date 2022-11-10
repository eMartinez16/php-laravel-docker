<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{

    public function index()
    {
        if (!auth()->check() || (auth()->user()->estado !== 'activo')) {
            notify()->error("No puedes logear hasta que un administrador te acepte.","Error: ","topRight"); // FIXME: no sale el cartel
        }
        
        Session::flush();
        
        Auth::logout();

        return redirect('admin');
    }
}
