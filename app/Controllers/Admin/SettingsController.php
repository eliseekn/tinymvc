<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Support\Session;
use Framework\Routing\Controller;
use Framework\Support\Encryption;
use App\Database\Models\UsersModel;
use App\Database\Models\CountriesModel;
use App\Requests\SaveSettings;

class SettingsController extends Controller
{
	/**
	 * display user settings page
	 *
	 * @param  int $id
	 * @return void
	 */
	public function index(int $id): void
	{
        $user = UsersModel::find('id', $id)->single();
		
		if ($user === false) {
			$this->toast(__('user_not_found'))->error();
			$this->redirect()->only();
        }
        
        $countries = CountriesModel::select()->orderAsc('name')->all();
		$this->render('admin/settings', compact('user', 'countries'));
    }
    
    /**
     * update user settings
     *
     * @param  int $id
     * @return void
     */
    public function update(int $id): void
    {
        $validate = SaveSettings::validate(Request::getFields());
        
        if ($validate->fails()) {
            $this->redirect()->withError($validate::$errors);
        }

        $data = [
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'country' => Request::getField('country'),
            'company' => Request::getField('company'),
            'phone' => Request::getField('phone'),
            'two_factor' => Request::hasField('two-factor') ? 1 : 0,
            'lang' => Request::getField('lang'),
            'timezone' => Request::getField('timezone'),
            'currency' => Request::getField('currency'),
            'theme' => Request::getField('theme') ? 'dark' : 'light',
            'alerts' => Request::hasField('alerts') ? 1 : 0,
            'notifications_email' => Request::hasField('notifications-email') ? 1 : 0
		];
		
		if (!empty(Request::getField('password'))) {
			$data['password'] = Encryption::hash(Request::getField('password'));
		}

        UsersModel::update($data)->where('id', $id)->persist();

        $user = UsersModel::find('id', $id)->single();
    
        if (Session::getUser()->id === $id) {
            Session::setUser($user);
        }

        $this->toast(__('changes_saved'))->success();
        $this->redirect()->only();
    }
}
