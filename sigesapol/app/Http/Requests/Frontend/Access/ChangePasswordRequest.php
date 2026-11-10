<?php namespace App\Http\Requests\Frontend\Access;

use App\Http\Requests\Request;

/**
 * Class ChangePasswordRequest
 * @package App\Http\Requests\Frontend\Access
 */
class ChangePasswordRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 * SE MODIFICO LINEA 30
	 * Se aplica una validacion de una expresion regular como regla para el proceso del formulario que permite el cambio de contraseña.
	 * @return array
	 */
	public function rules()
	{
		return [
			'old_password'	=>  'required',
			'password'		=>	'required|regex:/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){10,20}$/|confirmed',
		];
	}

	/**
	 * Se aplican mensajes de respuesta en caso de que el el envio del formulario no cumpla las reglas establecidas.
	 * El mensaje para cuando no se cumplan las politicas, es el que se observa.
	 */
	public function messages()
	{
		return [
			'password.regex'=> 'La contraseña debe ser alfanumerica, debe tener entre 10 y 20 caracteres,debe tener una letra mayuscula como minimo y tambien debe tener un caracter especial.',
		];
	}

}