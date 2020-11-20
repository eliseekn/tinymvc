<?php

namespace App\Controllers\Admin;

use App\Requests\UpdateUser;
use Framework\Support\Session;
use Framework\Routing\Controller;
use Framework\Support\Encryption;
use App\Database\Models\UsersModel;
use App\Database\Models\CountriesModel;

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
			$this->redirectBack()->withError(__('user_not_found'), '', 'toast');
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
        $validator = UpdateUser::validate($this->request->inputs());
        
        if ($validator->fails()) {
            $this->redirectBack()->withError($validator->errors());
        }

        $data = [
            'name' => $this->request->name,
            'email' => $this->request->email,
            'country' => $this->request->country,
            'company' => $this->request->company ?? '',
            'phone' => $this->request->phone,
            'two_factor' => $this->request->has('two-factor') ? 1 : 0,
            'lang' => $this->request->lang,
            'timezone' => $this->request->timezone,
            'currency' => $this->request->currency,
            'theme' => $this->request->theme ? 'dark' : 'light',
            'alerts' => $this->request->has('alerts') ? 1 : 0,
            'notifications_email' => $this->request->has('notifications-email') ? 1 : 0
		];
		
		if (!empty($this->request->password)) {
			$data['password'] = Encryption::hash($this->request->password);
		}

        UsersModel::update($data)->where('id', $id)->persist();

        $user = UsersModel::find('id', $id)->single();
    
        if (Session::getUser()->id === $id) {
            Session::setUser($user);
        }

        $this->redirectBack()->withSuccess(__('changes_saved'), '', 'toast');
    }
}
