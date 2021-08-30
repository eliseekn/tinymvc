<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Core\Http\Request;
use Core\Support\Alert;
use App\Mails\TokenMail;
use App\Database\Models\User;
use App\Database\Models\Token;
use App\Http\Validators\AuthRequest;

/**
 * Manage password forgot
 */
class ForgotPasswordController
{
	public function notify(Request $request)
	{
		$token = generate_token();

		if (TokenMail::send($request->email, $token)) {
            Token::create([
                'email'=> $request->email,
                'token' => $token,
                'expire' => Carbon::now()->addHour()->toDateTimeString()
            ]);

            Alert::default(__('password_reset_link_sent'))->success();
			redirect()->back()->go();
		}
        
        Alert::default(__('password_reset_link_not_sent'))->error();
        redirect()->back()->go();
	}
	
	public function reset(Request $request)
	{
        if (!$request->has('email', 'token')) {
            response()->send('Bad Request', [], 400);
        }

        $token = Token::findBy('email', $request->email);

        if (!$token || $token->token !== $request->token) {
			response()->send(__('invalid_password_reset_link'), [], 400);
		}

		if (Carbon::parse($token->expire)->gt(Carbon::now())) {
			response()->send(__('expired_password_reset_link'), [], 400);
		}

        $token->delete();

		render('auth.password.new', ['email' => $request->email]);
	}
	
	public function update(Request $request)
	{
		AuthRequest::validate($request->except('csrf_token'))->redirectOnFail();
        $user = User::findBy('email', $request->email);

        if (!$user) {
            Alert::default(__('password_not_reset'))->error();
            redirect()->back()->go();
        }

        $user->password = hash_pwd($request->password);
        $user->save();

        Alert::default(__('password_reset'))->success();
        redirect()->url('login')->go();
	}
}
