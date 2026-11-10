<?php

namespace App\Http\Middleware;

use App\Services\ServerResourceMonitor;
use Closure;
use Illuminate\Support\Facades\Log;

class CheckServerResources
{
    protected $serverResourceMonitor;

    public function __construct(ServerResourceMonitor $serverResourceMonitor)
    {
        $this->serverResourceMonitor = $serverResourceMonitor;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $cpuLimit = 85; // Límite de CPU por defecto
        $ramLimit = 85; // Límite de RAM por defecto

        $cpuUsage = $this->serverResourceMonitor->getCpuUsage();
        $ramUsage = $this->serverResourceMonitor->getMemoryUsage();

        // Registra el uso
        Log::info('Uso de CPU: ' . $cpuUsage . '%');
        Log::info('Uso de RAM: ' . $ramUsage . '%');

        // Verifica si el uso excede los límites
        if ($cpuUsage > $cpuLimit || $ramUsage > $ramLimit) {
            return response()->json([
                "code" => 503,
                'message' => 'Nuestro Servidor esta temporalmente sobrecargado por la alta demanda. A favor de intentar nuevamente más tarde  en lo que se libera de trabajos, si está firmando masivamente inténtelo individualmente hasta que el servidor se libere'
            ], 503);
        }

        return $next($request);
    }
}
