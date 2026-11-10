<?php namespace App\Listeners\Frontend\Auth;

// use Illuminate\Queue\InteractsWithQueue;
use App\Events\Frontend\Auth\UserLoggedOut;
use App\Models\Logeo;
use Carbon\Carbon;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

/**
 * Class UserLoggedOutHandler
 * @package App\Listeners\Frontend\Auth
 */
// class UserLoggedOutHandler implements ShouldQueue {
class UserLoggedOutHandler {

	// use InteractsWithQueue;

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  UserLoggedOut  $event
	 * @return void
	 */
	public function handle(UserLoggedOut $event)
	{
		$updateLogeo = Logeo::where('id_user', $event->user->id)->orderBy('id', 'desc')->first();
		$updateLogeo->update([
			'logout_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		]);
		Cookie::queue(Cookie::forget('last_user_id_cookie'));
		Cookie::queue(Cookie::forget('was_logged_out'));
		Log::info("User Logged Out: ".$event->user->name);
	}
}
