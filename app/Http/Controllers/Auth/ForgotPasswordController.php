<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Mails\TokenMail;
use Core\Http\Request;
use App\Database\Repositories\Users;
use App\Http\Validators\AuthRequest;
use App\Database\Repositories\Tokens;

/**
 * Manage password forgot
 */
class ForgotPasswordController
{
	/**
	 * send reset password link notification
	 *
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Tokens $tokens
	 * @return void
	 */
	public function notify(Request $request, Tokens $tokens): void
	{
		$token = random_string(50, true);

		if (TokenMail::send($request->email, $token)) {
            $tokens->store($request->email, $token, Carbon::now()->addHour()->toDateTimeString());
			redirect()->back()->withAlert('success', __('password_reset_link_sent'))->go();
		} 
        
		redirect()->back()->withAlert('error', __('password_reset_link_not_sent'))->go();
	}
	
	/**
	 * reset password
	 *
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Tokens $tokens
	 * @return void
	 */
	public function reset(Request $request, Tokens $tokens): void
	{
        if (!$request->has('email', 'token')) {
            response()->send('Bad Request', [], 400);
        }

        $token = $tokens->findOneByEmail($request->email);

        if (!$token || $token->token !== $request->token) {
			response()->send(__('invalid_password_reset_link'), [], 400);
		}

		if (Carbon::parse($token->expire)->gt(Carbon::now())) {
			response()->send(__('expired_password_reset_link'), [], 400);
		}

		$tokens->flush($token->token);
		render('auth.password.new', ['email' => $token->email]);
	}
	
	/**
	 * update user password
	 *
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
	 * @return void
	 */
	public function update(Request $request, Users $users): void
	{
		AuthRequest::validate($request->except('csrf_token'))->redirectOnFail();

        $users->updateWhere($request->only('email'), 
            ['password' => pwd_hash($request->password)]
        );

        redirect()->url('login')->withAlert('success', __('password_reset'))->go();
	}
}
