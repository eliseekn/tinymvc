<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use App\Requests\CreateUserRequest;
use App\Requests\UpdateUserRequest;
use App\Database\Models\UsersModel;

class UsersController
{
	/**
	 * display new user page
	 * 
	 * @return void
	 */
	public function new(): void
	{
		View::render('admin/users/new');
	}
	
	/**
	 * display view user page
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		if (!UsersModel::exists('id', $id)) {
			Redirect::back()->withError('This user does not exists');
		}

		View::render('admin/users/view', [
			'user' => UsersModel::find($id)
		]);
	}
	
	/**
	 * display edit user page
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function edit(int $id): void
	{
		if (!UsersModel::exists('id', $id)) {
			Redirect::back()->withError('This user does not exists');
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
		$validate = CreateUserRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
            Redirect::back()->withError($validate);
        }

		if (UsersModel::exists('email', Request::getField('email'))) {
			Redirect::back()->withError('This email address already exists');
		}

	    UsersModel::create([
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'password' => hash_string(Request::getField('password')),
			'role' => Request::getField('role')
        ]);

        Redirect::back()->withSuccess('The user has been created successfully');
    }
    
	/**
	 * update user
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
		$validate = UpdateUserRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
            Redirect::back()->withError($validate);
        }

		if (!UsersModel::exists('id', $id)) {
			Redirect::back()->withError('This user does not exists');
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
        Redirect::back()->withSuccess('The user has been updated successfully');
    }

	/**
	 * delete user
	 *
     * @param  int|null $id
	 * @return void
	 */
	public function delete(?int $id = null): void
	{
		if (!is_null($id)) {
			if (!UsersModel::exists('id', $id)) {
				Redirect::back()->withError('This user does not exists');
			}
	
			UsersModel::delete($id);
			Redirect::back()->withSuccess('The user has been deleted successfully');
		} else {
			$users_id = json_decode(Request::getRawData(), true);
			$users_id = $users_id['items'];

			foreach ($users_id as $id) {
				UsersModel::delete($id);
			}
			
			create_flash_messages('success', 'The selected users have been deleted successfully');
		}
	}

	/**
	 * import users data
	 *
	 * @return void
	 * @link https://www.php.net/manual/en/function.str-getcsv.php
	 */
	public function import(): void
	{
		$file = Request::getFile('file', ['csv']);

		if (!$file->isAllowed()) {
			Redirect::back()->withError('Only file of type extension ".csv" are allowed');
		}

		if (!$file->isUploaded()) {
			Redirect::back()->withError('Failed to import users data');
		}

		$csv = array_map('str_getcsv', file($file->getTempFilename()));

		array_walk($csv, function(&$a) use ($csv) {
			$a = array_combine($csv[0], $a);
		});

		//remove header
		array_shift($csv);

		foreach ($csv as $row) {
			UsersModel::create([
				'name' => $row['Name'],
				'email' => $row['Email'],
				'password' => hash_string($row['Password'])
			]);
		}

		Redirect::back()->withSuccess('The users have been imported successfully');
	}
		
	/**
	 * export users data
	 *
	 * @return void
	 * @link https://www.php.net/manual/en/function.fputcsv.php
	 */
	public function export(): void
	{
		foreach (UsersModel::findAll(['name', 'ASC']) as $users) {
			$data[] = [
				$users->name, 
				$users->email, 
				$users->role, 
				$users->created_at
			];
		}

		generate_csv('users.csv', $data, [
			'Name', 'Email', 'Role', 'Created at'
		]);
	}
}
