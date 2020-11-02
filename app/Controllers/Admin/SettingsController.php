<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Support\Session;
use Framework\Routing\Controller;
use Framework\Support\Encryption;
use App\Database\Models\UsersModel;

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

		$this->render('admin/settings', compact('user'));
    }
    
    /**
     * update user settings
     *
     * @param  int $id
     * @return void
     */
    public function update(int $id): void
    {
        $data = [
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'two_factor' => Request::hasField('two-factor') ? 1 : 0,
            'lang' => Request::getField('lang'),
            'timezone' => Request::getField('timezone'),
            'currency' => Request::getField('currency'),
            'theme' => Request::hasField('theme') ? 'dark' : 'light',
            'notifications' => Request::hasField('notifications') ? 1 : 0,
            'notifications_email' => Request::hasField('notifications_email') ? 1 : 0
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
