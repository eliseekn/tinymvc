<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use Framework\Http\Request;
use App\Requests\UpdateUser;
use App\Database\Models\Users;
use Framework\Support\Session;
use App\Helpers\CountriesHelper;
use Framework\Routing\Controller;

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
        $user = $this->model('users')->findSingle($id);
        $countries = CountriesHelper::all();
        $this->render('admin.account.settings', compact('user', 'countries'));
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
        UpdateUser::validate($request->inputs())->redirectOnFail();
        Users::updateSettings($request, $id);

        if (Auth::get()->id === $id) {
            Session::create('user', $this->model('users')->findSingle($id));
        }

        $this->log(__('changes_saved'));
        redirect()->back()->withToast(__('changes_saved'))->success();
    }
}
