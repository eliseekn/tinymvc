<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Mails\TokenMail;
use App\Requests\AuthRequest;
use App\Database\Models\Tokens;
use Framework\Http\Request;
use Framework\Routing\Controller;
use Framework\Support\Encryption;

/**
 * Manage password reset
 */
class PasswordController extends Controller
{
	/**
	 * send reset password link notification
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function notify(Request $request): void
	{
		$token = random_string(50, true);

		if (TokenMail::send($request->email, $token)) {
            Tokens::store($request->email, $token, Carbon::now()->addHour()->toDateTimeString());
			redirect()->back()->withAlert(__('password_reset_link_sent', true))->success('');
		} 
        
		redirect()->back()->withAlert(__('password_reset_link_not_sent', true))->error('');
	}
	
	/**
	 * reset password
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function reset(Request $request): void
	{
        $reset_token = Tokens::findSingleByEmail($request->email);

        if (!$reset_token || $reset_token->token !== $request->token) {
			response()->json(__('invalid_password_reset_link', true));
		}

		if ($reset_token->expires < Carbon::now()->toDateTimeString()) {
			response()->json(__('expired_password_reset_link', true));
		}

		Tokens::deleteByEmail($reset_token->email);
		$this->render('auth.password.new', ['email' => $reset_token->email]);
	}
	
	/**
	 * update user password
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function update(Request $request): void
	{
		AuthRequest::validate($request->inputs())->redirectOnFail();

        $this->model('users')->updateBy(
            ['email', $request->email], 
            ['password' => Encryption::hash($request->password)]
        );

        redirect()->url('login')->withAlert(__('password_resetted', true))->success('');
	}
}
