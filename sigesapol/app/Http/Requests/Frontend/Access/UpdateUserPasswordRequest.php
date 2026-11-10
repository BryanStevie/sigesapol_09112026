<?php namespace App\Http\Requests\Frontend\Access;

use App\Http\Requests\Request;

/**
 * Class ChangePasswordRequest
 * @package App\Http\Requests\Frontend\Access
 */
class UpdateUserPasswordRequest extends Request {

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
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'password'		=>	'required|regex:/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){10,20}$/|confirmed',
			'password_confirmation'	=>	'required|regex:/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){10,20}$/|min:10'
		];
	}

	public function messages()
	{
		return [
			'password.regex'=> 'La contraseña debe ser alfanumerica, debe tener entre 10 y 20 caracteres,debe tener una letra mayuscula como minimo y tambien debe tener un caracter especial.',
		];
	}
}