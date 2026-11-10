<?php namespace App\Listeners\Frontend\Auth;

// use Illuminate\Queue\InteractsWithQueue;
use App\Events\Frontend\Auth\UserLoggedIn;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cookie;

/**
 * Class UserLoggedInHandler
 * @package App\Listeners\Frontend\Auth
 */
// class UserLoggedInHandler implements ShouldQueue {
class UserLoggedInHandler {

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
	 * @param  UserLoggedIn  $event
	 * @return void
	 */
	public function handle(UserLoggedIn $event)
	{
		\Log::info("User Logged In: ".$event->user->name);
		Cookie::queue('last_user_id_cookie', $event->user->id, 60);
	}
}
