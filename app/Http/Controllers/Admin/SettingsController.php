<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Auth;
use Framework\Http\Request;
use App\Http\Validators\UpdateUser;
use Framework\System\Session;
use App\Helpers\CountriesHelper;
use Framework\Routing\Controller;
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
        $data = $this->users->findSingle($id);
        $countries = CountriesHelper::all();
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
        UpdateUser::register($id)->validate($request->inputs())->redirectOnFail();
        $this->users->updateSettings($request, $id);

        if (Auth::get('id') === $id) {
            Session::create('user', $this->users->findSingle($id));
        }

        $this->log(__('changes_saved'));
        redirect()->back()->withToast('success', __('changes_saved'))->go();
    }
}
