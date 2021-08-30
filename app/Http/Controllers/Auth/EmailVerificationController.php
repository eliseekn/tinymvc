<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Core\Http\Request;
use Core\Support\Alert;
use App\Mails\WelcomeMail;
use App\Database\Models\User;
use App\Database\Models\Token;
use App\Mails\VerificationMail;

/**
 * Manage email verification link
 */
class EmailVerificationController
{
    public function notify(Request $request)
    {
        $token = generate_token();

        if (VerificationMail::send($request->email, $token)) {
            Token::create([
                'email'=> $request->email,
                'token' => $token,
                'expire' => Carbon::now()->addDay()->toDateTimeString()
            ]);

            Alert::default(__('email_verification_link_sent'))->success();
            redirect()->url('login')->go();
        }
        
        Alert::default(__('email_verification_link_not_sent'))->error();
        redirect()->back()->go();
    }

	public function verify(Request $request)
	{
        $user = User::findBy('email', $request->queries('email'));

		if (!$user) {
            Alert::default(__('account_not_found'))->error();
            redirect()->url('signup')->go();
        }

        $user->verified = 1;
        $user = $user->save();

        WelcomeMail::send($user->email, $user->name);

        Alert::default(__('verified'))->success();
        redirect()->url('login')->go();
    }
}
