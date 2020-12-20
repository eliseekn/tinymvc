<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\Activity;
use App\Requests\UpdateUser;
use Framework\HTTP\Redirect;
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
        $user = UsersModel::find($id)->single();
		
		if ($user === false) {
			Redirect::back()->withToast(__('user_not_found'))->success();
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
            Redirect::back()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withToast(__('changes_not_saved'))->error();
        }

        $data = [
            'name' => $this->request->name,
            'email' => $this->request->email,
            'country' => $this->request->country,
            'company' => $this->request->company ?? '',
            'phone' => $this->request->phone,
            'two_steps' => $this->request->has('two_steps') ? 1 : 0,
            'lang' => $this->request->lang,
            'timezone' => $this->request->timezone,
            'currency' => $this->request->currency,
            'dark_theme' => $this->request->has('dark_theme') ? 1 : 0,
            'alerts' => $this->request->has('alerts') ? 1 : 0,
            'email_notifications' => $this->request->has('email-notifications') ? 1 : 0
		];
		
		if (!empty($this->request->password)) {
			$data['password'] = Encryption::hash($this->request->password);
		}

        UsersModel::update($data)->where('id', $id)->persist();

        if (Session::get('user')->id == $id) {
            Auth::set(UsersModel::find($id)->single());
        }

        Activity::log('Settings saved');
        Redirect::back()->withToast(__('changes_saved'))->success();
    }
}
