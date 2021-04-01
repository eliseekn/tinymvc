<?php

namespace App\Controllers\Admin;

use Exception;
use App\Helpers\Auth;
use App\Requests\UpdateUser;
use Framework\Support\Session;
use Framework\Routing\Controller;
use Framework\Support\Encryption;
use App\Helpers\CountriesHelper;

class SettingsController extends Controller
{
	/**
	 * index
	 *
	 * @param  int $id
	 * @return void
	 */
	public function index(int $id): void
	{
        try {
            $user = $this->model('users')->findOrFail('id', $id);
            $countries = CountriesHelper::all();
            $this->render('admin.account.settings', compact('user', 'countries'));
        } catch (Exception $e) {
            $this->back()->withToast(__('user_not_found'))->error();
        }
    }
    
    /**
     * update
     *
     * @param  int $id
     * @return void
     */
    public function update(int $id): void
    {
        UpdateUser::validate($this->request->inputs())->redirectOnFail();
        
        $data = [
            'name' => $this->request->name,
            'email' => $this->request->email,
            'country' => $this->request->country,
            'company' => $this->request->company ?? '',
            'phone' => $this->request->phone,
            'two_steps' => $this->request->exists('two_steps') ? 1 : 0,
            'lang' => $this->request->lang,
            'timezone' => $this->request->timezone,
            'currency' => $this->request->currency,
            'dark_theme' => $this->request->exists('dark_theme') ? 1 : 0,
            'alerts' => $this->request->exists('alerts') ? 1 : 0,
            'email_notifications' => $this->request->exists('email-notifications') ? 1 : 0
		];
		
		if (!empty($this->request->password)) {
			$data['password'] = Encryption::hash($this->request->password);
		}

        $this->model('users')->updateIfExists($id, $data);

        if (Auth::get()->id === $id) {
            Session::create('user', $this->model('users')->findSingle($id));
        }

        $this->log(__('changes_saved'));
        $this->back()->withToast(__('changes_saved'))->success();
    }
}
