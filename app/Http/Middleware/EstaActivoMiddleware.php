<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EstaActivoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || (auth()->user()->estado !== 'activo')) {
            notify()->error("No puedes logear hasta que un administrador te acepte.","Error: ","topRight");
            return redirect('admin/logout');
        }
        
       return $next($request);
    }
    
}
