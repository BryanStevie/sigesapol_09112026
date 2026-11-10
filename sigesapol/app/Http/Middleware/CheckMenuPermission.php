<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CheckMenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $menuId, $action = "lectura")
    {
      $permissions = Session::get('permissions', []);

      $permisoEncontrado = null;

      foreach ($permissions as $perm) {
        if ($perm->idmenu == $menuId && intval($perm->lectura) === 1) {
            $permisoEncontrado = $perm;
            // Log::info(["permisoEncontrado: " => $permisoEncontrado]);
            break;
        }
      }

      if ($permisoEncontrado === null) {
        Session::flush();
        return Redirect::to('/auth/login')->with('error', 'Sesión expirada o sin permisos');
      }

      return $next($request);
    }
}
