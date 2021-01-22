<?php

namespace App\Controllers\Admin;

use App\Helpers\Activity;
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
        $user = UsersModel::findSingle($id);
		
		if ($user === false) {
			$this->redirect()->withToast(__('user_not_found'))->success();
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
            $this->redirect()->withErrors($validator->errors())->withInputs($validator->inputs())
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
            Session::create('user', UsersModel::findSingle($id));
        }

        Activity::log(__('changes_saved'));
        $this->redirect()->withToast(__('changes_saved'))->success();
    }
}
