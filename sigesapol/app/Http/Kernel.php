<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Class Kernel
 * @package App\Http
 */
class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
		\App\Http\Middleware\EncryptCookies::class,
		\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
		\Illuminate\Session\Middleware\StartSession::class,
		\Illuminate\View\Middleware\ShareErrorsFromSession::class,
		\App\Http\Middleware\VerifyCsrfToken::class,
		\App\Http\Middleware\LocaleMiddleware::class,

		// A�ADIENDO LA CLASE SHAREMENU PARA EVITAR ERRORES
		\App\Http\Middleware\ShareMenu::class, // registro para el midleware

		\App\Http\Middleware\GlobalExceptionHandler::class,
	/**	\App\Http\Middleware\HttpsProtocol::class,*/
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		/**
		 * Default laravel route middleware
		 */
		'auth' => \App\Http\Middleware\Authenticate::class,
		'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
		'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

		/**
		 * Access Middleware
		 */
		'access.routeNeedsRole' => \App\Http\Middleware\RouteNeedsRole::class,
		'access.routeNeedsPermission' => \App\Http\Middleware\RouteNeedsPermission::class,
		'medico_active' => \App\Http\Middleware\MedicoActivo::class,

		/**
		 * Check Resources Middleware
		 */
		'check.server.resources' => \App\Http\Middleware\CheckServerResources::class,
		
    /**
     * Check menu permission middleware
     * This middleware checks if the user has permission to access a specific menu item.
     */
    'menu.permission' => \App\Http\Middleware\CheckMenuPermission::class,
	];
	
}
