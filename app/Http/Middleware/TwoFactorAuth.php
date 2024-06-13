<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class TwoFactorAuth
{
    /** 
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   $session = Session::get('auth_passed');
        if (!Session::has('auth_passed')) {
            return response()->json(['success' => false, 'message' => 'two factor failed', 'session' => $session ]);
        }   

        return $next($request);
    }   
}
?>