<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Authenticatable;
use App\Models\User;
use Laratrust\Models\LaratrustRole;

class adminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if($user->hasRole('superadministrator')){
            return $next($request);
        }elseif ($user->hasRole('administrator')){
            return $next($request);
        }
    
        return redirect('/dashboard');
    }
}
