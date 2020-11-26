<?php

namespace App\Controllers\Admin;

use App\Requests\UpdateUser;
use Framework\Support\Session;
use App\Helpers\ActivityHelper;
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
			$this->redirectBack()->withToast(__('user_not_found'))->success();
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
            $this->redirectBack()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withToast(__('changes_not_saved'))->error();
        }

        $data = [
            'name' => $this->request->name,
            'email' => $this->request->email,
            'country' => $this->request->country,
            'company' => $this->request->company ?? '',
            'phone' => $this->request->phone,
            'two_steps' => $this->request->has('two-steps') ? 1 : 0,
            'lang' => $this->request->lang,
            'timezone' => $this->request->timezone,
            'currency' => $this->request->currency,
            'theme' => $this->request->theme ? 'dark' : 'light',
            'alerts' => $this->request->has('alerts') ? 1 : 0,
            'email_notifications' => $this->request->has('email-notifications') ? 1 : 0
		];
		
		if (!empty($this->request->password)) {
			$data['password'] = Encryption::hash($this->request->password);
		}

        UsersModel::update($data)->where('id', $id)->persist();

        $user = UsersModel::find('id', $id)->single();
    
        if (Session::get('user')->id === $id) {
            Session::create('user', $user);
        }

        ActivityHelper::log('Settings saved');
        $this->redirectBack()->withToast(__('changes_saved'))->success();
    }
}
