<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Core\Http\Request;
use App\Mails\TokenMail;
use App\Database\Models\User;
use App\Database\Repositories\TokenRepository;
use App\Database\Repositories\UserRepository;
use App\Http\Validators\AuthRequest;

/**
 * Manage password forgot
 */
class ForgotPasswordController
{
	/**
	 * Send reset password link notification
	 */
	public function notify(Request $request, TokenRepository $tokenRepository)
	{
		$token = generate_token();

		if (TokenMail::send($request->email, $token)) {
            $tokenRepository->store($request->email, $token, Carbon::now()->addHour()->toDateTimeString());
			redirect()->back()->withAlert('success', __('password_reset_link_sent'))->go();
		} 
        
		redirect()->back()->withAlert('error', __('password_reset_link_not_sent'))->go();
	}
	
	public function reset(Request $request, TokenRepository $tokenRepository)
	{
        if (!$request->has('email', 'token')) {
            response()->send('Bad Request', [], 400);
        }

        $token = $tokenRepository->findByEmail($request->email);

        if (!$token || $token->token !== $request->token) {
			response()->send(__('invalid_password_reset_link'), [], 400);
		}

		if (Carbon::parse($token->expire)->gt(Carbon::now())) {
			response()->send(__('expired_password_reset_link'), [], 400);
		}

        $token->delete();

		render('auth.password.new', ['email' => $token->email]);
	}
	
	public function update(Request $request, UserRepository $userRepository)
	{
		AuthRequest::validate($request->except('csrf_token'))->redirectOnFail();
        $user = $userRepository->findByEmail($request->email);

        if (!$user) {
		    redirect()->back()->withAlert('error', __('password_not_reset'))->go();
        }

        $user->password = hash_pwd($request->password);
        $user->save();

        redirect()->url('login')->withAlert('success', __('password_reset'))->go();
	}
}
