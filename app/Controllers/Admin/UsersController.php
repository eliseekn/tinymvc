<?php

namespace App\Controllers\Admin;

use Framework\Http\Request;
use Framework\Routing\View;
use Framework\Http\Redirect;
use App\Database\Models\UsersModel;
use App\Validators\CreateUserForm;
use App\Validators\UpdateUserForm;

class UsersController
{
	/**
	 * display edit user page
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function edit(int $id): void
	{
		if (!UsersModel::exists('id', $id)) {
			Redirect::back()->withError('Failed to get user infos. This user does not exists in database.');
		}

		View::render('admin/users/edit', [
			'user' => UsersModel::find($id)
		]);
	}

	/**
	 * create new user
	 *
	 * @return void
	 */
	public function create(): void
	{
		CreateUserForm::validate([
			'redirect' => 'back'
		]);

		if (UsersModel::exists('email', Request::getField('email'))) {
			Redirect::back()->withError('Failed to create user. This user already exists in database.');
		}

	    UsersModel::insert([
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'password' => hash_string(Request::getField('password'))
        ]);

        Redirect::back()->withSuccess('The user has been created successfully.');
    }
    
	/**
	 * update user
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
		UpdateUserForm::validate([
			'redirect' => 'back'
		]);

		if (!UsersModel::exists('id', $id)) {
			Redirect::back()->withError('Failed to update user. This user does not exists in database.');
		}

		if (UsersModel::exists('email', Request::getField('email'))) {
			Redirect::back()->withError('Failed to update user. This email address is already used by another user.');
		}

		$data = [
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'role' => Request::getField('role'),
            'updated_at' => date("Y-m-d H:i:s")
		];
		
		if (!empty(Request::getField('password'))) {
			$data['password'] = hash_string(Request::getField('password'));
		}

		UsersModel::update($id, $data);
        Redirect::back()->withSuccess('The user has been updated successfully.');
    }

	/**
	 * delete user
	 *
     * @param  int $id
	 * @return void
	 */
	public function delete(int $id): void
	{
		if (!UsersModel::exists('id', $id)) {
			Redirect::back()->withError('Failed to delete user. This user does not exists in database.');
		}

        UsersModel::delete($id);
        Redirect::back()->withSuccess('The user has been deleted successfully.');
    }
}
