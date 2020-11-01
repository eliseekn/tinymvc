<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\Support\Alert;
use Framework\Support\Session;
use Framework\Support\Encryption;
use App\Database\Models\UsersModel;

class SettingsController
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
			Alert::toast('This user was not found', 'User not found')->error();
			Redirect::back()->only();
		}

		View::render('admin/settings', compact('user'));
    }
    
    /**
     * update settings parameters
     *
     * @param  string $update
     * @param  int $id
     * @return void
     */
    public function update(string $update, int $id): void
    {
        $data = [];

        switch ($update) {
            case 'profile':
                $data = [
                    'name' => Request::getField('name'),
                    'email' => Request::getField('email')
                ];

                break;
            case 'security':
                $data = [
                    'password' => Encryption::hash(Request::getField('password')),
                    'two_factor' => Request::hasField('two-factor') ? 1 : 0
                ];

                break;
            case 'preferences':
                $data = [
                    'lang' => Request::getField('lang'),
                    'timezone' => Request::getField('timezone'),
                    'currency' => Request::getField('currency')
                ];

                break;
            case 'dashboard':
                $data = [
                    'theme' => Request::hasField('theme') ? 'dark' : 'light',
                    'notifications' => Request::hasField('notifications') ? 1 : 0,
                    'notifications_email' => Request::hasField('notifications_email') ? 1 : 0
                ];

                break;
            default:
                Alert::toast('This parameter was not found', 'Parameter not found')->error();
                break;
        }

        if (!empty($data)) {
            UsersModel::update($data)->where('id', $id)->persist();

            $user = UsersModel::find('id', $id)->single();
        
            if (Session::getUser()->id === $id) {
                Session::setUser($user);
            }

            Alert::toast('Your changes have been saved succesfully', 'Changes saved')->success();
        }

        Redirect::back()->only();
    }
}
