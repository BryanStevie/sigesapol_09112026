<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

/**
 * Class VerifyCsrfToken
 * @package App\Http\Middleware
 */
class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	/*
	protected $except = [
        'ExcelController/*',
        'http://dev-sgf-ds-prod.saludpol.gob.pe:8092/upload_firma',
    ];
	*/

	protected $except = [
		'refirma/*',
		'/refirma/*',
	];
	
	private $openRoutes = ['upload_firma','upload_firma_openpol'];


	public function handle($request, Closure $next)
	{
		
		foreach($this->openRoutes as $route) {	
		  if ($request->is($route)) {
			return $next($request);
		  }
		}
	
		return parent::handle($request, $next);
	}
}