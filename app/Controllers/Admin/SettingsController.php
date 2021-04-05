<?php

namespace App\Controllers\Admin;

use Exception;
use App\Helpers\Auth;
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
        try {
            $user = $this->model('users')->findOrFail('id', $id);
            $countries = CountriesHelper::all();
            $this->render('admin.account.settings', compact('user', 'countries'));
        } catch (Exception $e) {
            $this->redirect()->back()->withToast(__('user_not_found'))->error();
        }
    }
    
    /**
     * update
     *
     * @param  int $id
     * @return void
     */
    public function update(int $id): void
    {
        UpdateUser::validate($this->request()->inputs())->redirectOnFail();
        Users::updateSettings($this->request(), $id);

        if (Auth::get()->id === $id) {
            Session::create('user', $this->model('users')->findSingle($id));
        }

        $this->log(__('changes_saved'));
        $this->redirect()->back()->withToast(__('changes_saved'))->success();
    }
}
