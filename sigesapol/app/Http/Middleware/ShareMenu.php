<?php

namespace App\Http\Middleware;

use App\Models\Farmacia;
use App\Models\Menu;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ShareMenu
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
        if (Auth::check()) {
            $user = Auth::user();
            $menu = Menu::getMenuByUsuario($user->id);
            $farmacia = Farmacia::find($user->id_farmacia);
            $fechaHoy = $this->obtenerFechaHoy();

            View::share([
                'menu' => $menu,
                'farmacia' => $farmacia,
                'fechaHoy' => $fechaHoy,
                'user' => $user,
            ]);
        }

        return $next($request);
    }

    private function obtenerFechaHoy()
    {
        $arrayMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $arrayDias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return $arrayDias[date('w')] . ' ' . date('d') . ' de ' . $arrayMeses[date('m') - 1] . ' de ' . date('Y');
    }
}
