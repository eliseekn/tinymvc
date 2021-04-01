<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\Auth;
use App\Mails\WelcomeMail;
use App\Requests\AuthRequest;
use App\Requests\RegisterUser;
use Framework\Routing\Controller;
use App\Mails\EmailConfirmationMail;

/**
 * Manage user authentication
 */
class AuthController extends Controller
{
	/**
	 * authenticate user
	 * 
	 * @return void
	 */
	public function authenticate(): void
	{
        AuthRequest::validate($this->request->inputs())->redirectOnFail();
        Auth::attempt($this->request);
    }
        
    /**
     * register new user
     *
     * @return void
     */
    public function register(): void
    {
        $validator = RegisterUser::validate($this->request->inputs())->redirectOnFail();
        
        if (!Auth::create($this->request)) {
            $this->back()->withInputs($validator->inputs())
                ->withAlert(__('user_already_exists', true))->error('');
        }

        if ($this->model('users')->count()->single()->value === 1) {
            $this->redirect('admin/dashboard');
        }

        if (config('security.auth.email_confirmation') === false) {
            WelcomeMail::send($this->request->email, $this->request->name);
            $this->redirect('admin/dashboard')->withAlert(__('user_registered', true))->success('');
        } else {
            $token = random_string(50, true);

            if (EmailConfirmationMail::send($this->request->email, $token)) {
                $this->model('tokens')->insert([
                    'email' => $this->request->email,
                    'token' => $token,
                    'expires' => Carbon::now()->addDay()->toDateTimeString()
                ]);

                $this->redirect('admin/dashboard')->withAlert(__('confirm_email_link_sent', true))->success('');
            } else {
                $this->redirect('admin/dashboard')->withAlert(__('confirm_email_link_not_sent', true))->error('');
            }
        }
    }
	
	/**
	 * logout
	 *
     * @param  string|null $redirect
	 * @return void
	 */
	public function logout(?string $redirect = null): void
	{
		Auth::forget();

        $redirect = is_null($redirect) ? 'login' : $redirect;
		$this->redirect($redirect)->only();
	}
}
