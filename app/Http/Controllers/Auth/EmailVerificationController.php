<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Core\Http\Request;
use App\Mails\WelcomeMail;
use App\Mails\VerificationMail;
use App\Database\Repositories\UserRepository;
use App\Database\Repositories\TokenRepository;

/**
 * Manage email verification link
 */
class EmailVerificationController
{
    /**
     * Send email verification link
     */
    public function notify(Request $request, TokenRepository $tokenRepository)
    {
        $token = generate_token();

        if (VerificationMail::send($request->email, $token)) {
            $tokenRepository->store($request->email, $token, Carbon::now()->addDay()->toDateTimeString());
            redirect()->url('login')->withAlert('success', __('email_verification_link_sent'))->go();
        }
        
        redirect()->back()->withAlert('error', __('email_verification_link_not_sent'))->go();
    }

	/**
	 * Check email verification link
	 */
	public function verify(Request $request, UserRepository $userRepository)
	{
        $user = $userRepository->findByEmail($request->queries('email'));

		if (!$user) {
            redirect()->url('signup')->withAlert('error', __('account_not_found'))->go();
        }

        $user->verified = 1;
        $user = $user->save();

        WelcomeMail::send($user->email, $user->name);
        redirect()->url('login')->withAlert('success', __('verified'))->go();
    }
}
