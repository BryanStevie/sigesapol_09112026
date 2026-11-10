<?php

namespace App\Http\Middleware;

use App\Models\Logeo;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Si el usuario estaba autenticado y la sesión expiró
        if (!Auth::check() && $request->hasSession() && !Session::has('was_logged_out')) {
            Log::info('Sesión expirada por inactividad.');
            $lastUserId = $request->cookie('last_user_id_cookie');
            Log::info(['lastUserId' => $lastUserId]);
            
            if ($lastUserId) {
                $lastLog = Logeo::where('id_user', $lastUserId)->orderBy('id', 'desc')->first();

                if (isset($lastLog)) {
                    $lastLog->update([
                        'logout_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
                Cookie::queue('was_logged_out', true, 60);
                Cookie::queue(Cookie::forget('last_user_id_cookie'));
            }
        }

        return $next($request);
    }
}
