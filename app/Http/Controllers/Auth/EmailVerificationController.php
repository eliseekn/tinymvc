<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Mails\WelcomeMail;
use Framework\Http\Request;
use App\Mails\VerificationMail;
use App\Database\Repositories\Users;
use App\Database\Repositories\Tokens;

/**
 * Manage email verification link
 */
class EmailVerificationController
{
    /**
     * send email verification link
     *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Tokens $tokens
     * @return void
     */
    public function notify(Request $request, Tokens $tokens): void
    {
        $token = random_string(50, true);

        if (VerificationMail::send($request->email, $token)) {
            $tokens->store($request->email, $token, Carbon::now()->addDay()->toDateTimeString());
            redirect()->url('login')->withAlert('success', __('email_verification_link_sent'))->go();
        }
        
        redirect()->back()->withAlert('error', __('email_verification_link_not_sent'))->go();
    }

	/**
	 * check email verification link
	 *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
	 * @return void
	 */
	public function verify(Request $request, Users $users): void
	{
        $user = $users->findOneByEmail($request->queries('email'));

		if (!$user) {
            redirect()->url('signup')->withAlert('error', __('account_not_found'))->go();
        }

        $users->updateWhere(['email', $user->email], ['email_verified' => 1]);
        WelcomeMail::send($user->email, $user->name);
        redirect()->url('login')->withAlert('success', __('email_verified'))->go();
    }
}
