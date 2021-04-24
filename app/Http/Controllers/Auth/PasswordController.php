<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Mails\TokenMail;
use Framework\Http\Request;
use Framework\System\Encryption;
use Framework\Routing\Controller;
use App\Database\Repositories\Users;
use App\Http\Validators\AuthRequest;
use App\Database\Repositories\Tokens;

/**
 * Manage password reset
 */
class PasswordController extends Controller
{
	/**
	 * send reset password link notification
	 *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Tokens $tokens
	 * @return void
	 */
	public function notify(Request $request, Tokens $tokens): void
	{
		$token = random_string(50, true);

		if (TokenMail::send($request->email, $token)) {
            $tokens->store($request->email, $token, Carbon::now()->addHour()->toDateTimeString());
			$this->redirect()->back()->withAlert('success', __('password_reset_link_sent', true))->go();
		} 
        
		$this->redirect()->back()->withAlert('error', __('password_reset_link_not_sent', true))->go();
	}
	
	/**
	 * reset password
	 *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Tokens $tokens
	 * @return void
	 */
	public function reset(Request $request, Tokens $tokens): void
	{
        $reset_token = $tokens->findSingleByEmail($request->email);

        if (!$reset_token || $reset_token->token !== $request->token) {
			$this->response()->json(__('invalid_password_reset_link', true));
		}

		if ($reset_token->expires < Carbon::now()->toDateTimeString()) {
			$this->response()->json(__('expired_password_reset_link', true));
		}

		$tokens->deleteByEmail($reset_token->email);
		$this->render('auth.password.new', ['email' => $reset_token->email]);
	}
	
	/**
	 * update user password
	 *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
	 * @return void
	 */
	public function update(Request $request, Users $users): void
	{
		AuthRequest::validate($request->inputs())->redirectOnFail();

        $users->updateBy(
            ['email', $request->email], 
            ['password' => Encryption::hash($request->password)]
        );

        redirect()->url('login')->withAlert('success', __('password_resetted', true))->go();
	}
}
