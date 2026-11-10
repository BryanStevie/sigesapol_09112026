<?php namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Error;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// ATENCION
		Validator::extend('gestacion_femenino', function($attribute, $value, $parameters, $validator) {
			$sexo = strval($parameters[0]);
			if ($sexo === 'M') {
				return false;
			}
			return true;
        }, 'Es incorrecto que un paciente con sexo Masculino tenga una GESTACIÓN');
		Validator::extend('gestacion_edad', function($attribute, $value, $parameters, $validator) {
			$edad = intval($parameters[0]);
			if ($edad < 12 || $edad > 55) {
				return false;
			}
			return true;
        }, 'El rango de edad permitida para registrar una GESTACIÓN es de 12 a 55 años');
		Validator::extend('puerpera_femenino', function($attribute, $value, $parameters, $validator) {
			$sexo = strval($parameters[0]);
			if ($sexo === 'M') {
				return false;
			}
			return true;
        }, 'Es incorrecto que un paciente con sexo Masculino tenga un PUERPERIO');
		Validator::extend('puerpera_edad', function($attribute, $value, $parameters, $validator) {
			$edad = intval($parameters[0]);
			if ($edad < 12 || $edad > 55) {
				return false;
			}
			return true;
        }, 'El rango de edad permitida para registrar un PUERPERIO es de 12 a 55 años');
		Validator::extend('fecha_maxima_hoy', function($attribute, $value, $parameters, $validator) {
			// $fechaParto = Carbon::createFromFormat('d-m-Y', $value);
			$fechaParto = strtotime($parameters[0]);
			$fechaActual = strtotime(Carbon::now()->format("d-m-Y"));
			if ($fechaParto > $fechaActual) {
				return false;
			}
			return true;
        }, 'La fecha de PARTO no puede ser mayor que la fecha actual.');

		// ATENCION - EVALUACION
		Validator::extend("hora_actual_atencion", function($attribute, $value, $parameters, $validator) {
			$hora_ingresada = strtotime($parameters[0]);
			error_log($hora_ingresada);
			error_log(strtotime(Carbon::now()->toTimeString()));
			if ($hora_ingresada > strtotime(Carbon::now()->toTimeString())) {
				return false;
			}
			return true;
		}, "La hora de registro no puede ser mayor que la hora actual.");

		Validator::extend("hora_puerpera", function($attribute, $value, $parameters, $validator) {
			$hora_ingresada = strtotime($parameters[0]);
			error_log($hora_ingresada."birt");
			error_log(strtotime(Carbon::now()->toTimeString()));
			if ($hora_ingresada > strtotime(Carbon::now()->toTimeString())) {
				return false;
			}
			return true;
		}, "La HORA DE PARTO no puede ser mayor que la hora actual.");

		Validator::extend("fecha_inicio_evaluacion", function($attribute, $value, $parameters, $validator) {
			$fecha_inicio_evaluacion = strtotime($parameters[0]);
			if ($fecha_inicio_evaluacion > strtotime(Carbon::now()->format("d-m-Y"))) {
				//dd($fecha_inicio_evaluacion, Carbon::now()->format("d-m-Y"));
				return false;
			}
			return true;
		}, "La fecha de inicio no puede ser mayor que la fecha actual.");

		Validator::extend("ultima_gestacion_limite", function($attribute, $value, $parameters, $validator) {
			$ultima_menstruacion = strtotime($parameters[0]);
			if ($ultima_menstruacion > strtotime(Carbon::now()->format("d-m-Y"))) {
				return false;
			}
			return true;
		}, "La fecha de la ultima menstruacion no debe exceder a la actual.");

		// TRIAJE
		Validator::extend('fecha_maxima_hoy_dd/mm/yyyy', function($attribute, $value, $parameters, $validator) {
			$fechaParto = Carbon::createFromFormat('d/m/Y', $value);
			$fechaActual = Carbon::now();
			if ($fechaParto > $fechaActual) {
				return false;
			}
			return true;
        }, 'La fecha de parto no puede ser mayor que la fecha actual.');
		
		// ALTA DE PACIENTE (tipo)
		Validator::extend('fecha_egreso_limite', function($attribute, $value, $parameters, $validator) {
			$fecha_egreso = strtotime(strval($parameters[0]));
			$fechaActual = strtotime(Carbon::now()->format("d-m-Y"));
			if ($fecha_egreso > $fechaActual) {
				return false;
			}
			error_log("no falla");
			return true;
        }, 'La fecha de EGRESO del paciente no puede ser mayor que la fecha actual.');
		Validator::extend("tipos_validos", function($attribute, $value, $parameters, $validator) {
			$id_tipo_egreso = intval($value);
			if (in_array($id_tipo_egreso, [1, 2, 3, 4, 6, 7])) {
				return true;
			}
			return false;
		}, "El tipo de EGRESO ingresado no es valido.");
		// ALTA (TIPOS DE EGRESO)
		Validator::extend("servicio_valido", function($attribute, $value, $parameters, $validator) {
			$servicio_hospitalizacion = $value === "OTRO" ? $value : intval($value);
			if ($servicio_hospitalizacion === "OTRO" || $servicio_hospitalizacion > 0) {
				return true;
			}
			return true;
		}, "El SERVICIO DE HOSPITALIZACION para el ALTA del paciente no es valido.");
		/*Validator::extend("hora_defuncion_valida", function($attribute, $value, $parameters, $validator) {
			$hora_actual = strval($parameters[0]);
			if ($hora_actual > Carbon::now()->toTimeString()) {
				return false;
			}
			return true;
		}, "La HORA DE DEFUNCION del paciente no debe exceder a la hora actual.");*/
		Validator::extend("fecha_defuncion_valida", function($attribute, $value, $parameters, $validator) {
			$fecha_egreso = strval($parameters[0]);
			$fechaActual = Carbon::now()->format("d-m-Y");
			//if ($fecha_egreso > $fechaActual) {
			//	return false;
			//}
			return true;
		}, "La FECHA DE DEFUNCION del paciente no debe exceder a la hora actual.");
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		if ($this->app->environment() == 'local') {
			$this->app->register(\Laracasts\Generators\GeneratorsServiceProvider::class);
		}
	}
}
