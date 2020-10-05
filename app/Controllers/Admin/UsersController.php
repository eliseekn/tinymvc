<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use App\Requests\CreateUserRequest;
use App\Requests\UpdateUserRequest;
use App\Database\Models\UsersModel;
use App\Helpers\ReportHelper;

class UsersController
{
	/**
	 * display new page
	 * 
	 * @return void
	 */
	public function new(): void
	{
		View::render('admin/users/new');
	}
	
	/**
	 * display edit page
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
	 * create
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

	   $id = UsersModel::create([
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'password' => hash_string(Request::getField('password')),
			'role' => Request::getField('role')
		]);

		Redirect::toUrl('/admin/users/view/' . $id)->withSuccess('The user has been created successfully');
    }
	
	/**
	 * read
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
	 * update
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
            'active' => Request::getField('account_state'),
            'updated_at' => date("Y-m-d H:i:s")
		];
		
		if (!empty(Request::getField('password'))) {
			$data['password'] = hash_string(Request::getField('password'));
		}

		UsersModel::update($id, $data);
        Redirect::toUrl('/admin/users/view/' . $id)->withSuccess('The user has been updated successfully');
    }

	/**
	 * delete
	 *
     * @param  int|null $id
	 * @return void
	 */
	public function delete(?int $id = null): void
	{
		if (!is_null($id)) {
			if (!UsersModel::exists('id', "$id")) {
				create_flash_messages('danger', 'This user does not exists');
			}
	
			UsersModel::delete($id);
			create_flash_messages('success', 'The user has been deleted successfully');
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
	 * import data
	 *
	 * @return void
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

		$function = ['App\Database\Models\UsersModel', 'create'];

		ReportHelper::import($file->getTempFilename(), $function, [
			'name' => 'Name', 
			'email' => 'Email', 
			'password' => 'Password'
		]);

		Redirect::back()->withSuccess('The users have been imported successfully');
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
		if (!empty(Request::getField('date_start')) && !empty(Request::getField('date_end'))) {
			$users = UsersModel::findDateRange(Request::getField('date_start'), Request::getField('date_end'));
			$filename = 'users_' . str_replace('-', '_', Request::getField('date_start')) . '-' . str_replace('-', '_', Request::getField('date_end')) . '.' . Request::getField('file_type');
		} else {
			$users = UsersModel::findAll(['name', 'ASC']);
			$filename = 'users_' . date('Y_m_d') . '.' . Request::getField('file_type');
		}

		ReportHelper::export($filename, $users, [
			'name' => 'Name', 
			'email' => 'Email', 
			'role' => 'Role', 
			'created_at' => 'Created at'
		]);
	}
}
