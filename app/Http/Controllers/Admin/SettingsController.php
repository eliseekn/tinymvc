<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\Activity;
use Framework\Http\Request;
use Framework\System\Session;
use App\Helpers\Countries;
use Framework\Routing\Controller;
use App\Http\Validators\UpdateUser;
use App\Database\Repositories\Users;

class SettingsController extends Controller
{
    /**
     * @var \App\Database\Repositories\Users $users
     */
    private $users;
    
    /**
     * __construct
     *
     * @param  \App\Database\Repositories\Users $users
     * @return void
     */
    public function __construct(Users $users)
    {
        $this->users = $users;
    }

	/**
	 * index
	 *
	 * @param  int $id
	 * @return void
	 */
	public function index(int $id): void
	{
        $data = $this->users->findOne($id);
        $countries = Countries::all();
        $this->render('admin.account.settings', compact('data', 'countries'));
    }
    
    /**
     * update
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return void
     */
    public function update(Request $request, int $id): void
    {
        UpdateUser::register($id)->validate($request->except('csrf_token'))->redirectOnFail();
        $this->users->updateSettings($request, $id);

        if (Auth::get('id') === $id) {
            Session::create('user', $this->users->findOne($id));
        }

        Activity::log(__('changes_saved'));
        $this->redirect()->back()->withToast('success', __('changes_saved'))->go();
    }
}
