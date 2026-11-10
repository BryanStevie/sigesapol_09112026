<?php

/**
 * Frontend Access Controllers
 */
$router->group(['namespace' => 'Auth'], function () use ($router)
{
	/**
	 * These routes require the user to be logged in
	 */
	$router->group(['middleware' => 'auth'], function ()
	{
		get('auth/logout', 'AuthController@getLogout');
		get('auth/password/change', 'PasswordController@getChangePassword');
		get('auth/password/actualizarClaveUsuario/{user_id}', 'PasswordController@actualizarClaveUsuario');
		post('auth/password/change', 'PasswordController@postChangePassword')->name('password.change');
		
		post('auth/password/updatePassword', 'PasswordController@updatePassword')->name('password.change_usu');
		
	});

	/**
	 * These reoutes require the user NOT be logged in
	 */
	$router->group(['middleware' => 'guest'], function () use ($router)
	{
		get('auth/login', 'AuthController@getLogin');
		get('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
		get('account/confirm/{token}', 'AuthController@confirmAccount')->name('account.confirm');
		get('account/confirm/resend/{user_id}', 'AuthController@resendConfirmationEmail')->name('account.confirm.resend');

		$router->controller('auth', 'AuthController');
		$router->controller('password', 'PasswordController');
	});
});