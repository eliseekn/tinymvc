<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use App\Helpers\ReportHelper;
use App\Requests\RegisterRequest;
use Framework\Support\Encryption;
use App\Database\Models\RolesModel;
use App\Database\Models\UsersModel;
use App\Requests\UpdateUserRequest;
use Framework\Support\Notification;
use Framework\Support\Session;

class UsersController
{
	/**
	 * display new page
	 * 
	 * @return void
	 */
	public function new(): void
	{
		View::render('admin/users/new', [
			'roles' => RolesModel::select()->all()
		]);
	}
	
	/**
	 * display edit page
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function edit(int $id): void
	{
		$user = UsersModel::find('id', $id)->single();
		$roles = RolesModel::select()->all();

		if ($user === false) {
			Notification::toast('This user does not exists', 'User not exists')->error();
			Redirect::back()->only();
		}

		View::render('admin/users/edit', compact('user', 'roles'));
	}

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
		$validate = RegisterRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
			Session::setErrors(Request::getFields());
            Redirect::back()->withError($validate);
        }

		if (UsersModel::find('email', Request::getField('email'))->exists()) {
			Notification::toast('This email address already exists', 'User not created')->error();
            Redirect::back()->only();
		}

	   $id = UsersModel::insert([
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'password' => hash_string(Request::getField('password')),
			'role' => Request::getField('role')
		]);

		Notification::toast('The user has been created successfully', 'User created')->success();
		Redirect::toUrl('admin/users/view/' . $id)->only();
    }
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		$user = UsersModel::find('id', $id)->single();
		
		if ($user === false) {
			Notification::toast('This user does not exists', 'User not exists')->error();
			Redirect::back()->only();
		}

		View::render('admin/users/view', compact('user'));
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

		if (!UsersModel::find('id', $id)->exists()) {
			Notification::toast('This user does not exists', 'User not exists')->error();
			Redirect::back()->only();
		}

		$data = [
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'role' => Request::getField('role'),
            'active' => Request::getField('account_state'),
            'updated_at' => date("Y-m-d H:i:s")
		];
		
		if (!empty(Request::getField('password'))) {
			$data['password'] = Encryption::hash(Request::getField('password'));
		}

		UsersModel::update($data)->where('id', '=', $id)->persist();

		Notification::toast('The user has been updated successfully', 'User updated')->success();
        Redirect::toUrl('admin/users/view/' . $id)->only();
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
			if (!UsersModel::find('id', $id)->exists()) {
				Notification::toast('This user does not exists', 'User not exists')->error();
			}
	
			UsersModel::delete()->where('id', '=', $id)->persist();
			Notification::toast('The user has been deleted successfully', 'User deleted')->success();
		} else {
			$users_id = json_decode(Request::getRawData(), true);
			$users_id = $users_id['items'];

			foreach ($users_id as $id) {
				UsersModel::delete()->where('id', '=', $id)->persist();
			}
			
			Notification::toast('The selected users have been deleted successfully', 'Users deleted')->success();
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
			Notification::toast('Only file of type extension .csv are allowed', 'File type error')->error();
            Redirect::back()->only();
		}

		if (!$file->isUploaded()) {
			Notification::toast('Failed to import users data', 'Users not imported')->error();
			Redirect::back()->only();
		}

		$function = ['App\Database\Models\UsersModel', 'create'];

		ReportHelper::import($file->getTempFilename(), $function, [
			'name' => 'Name', 
			'email' => 'Email', 
			'password' => 'Password'
		]);

		Notification::toast('The users have been imported successfully', 'Users imported')->success();
		Redirect::back()->only();
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
			$users = UsersModel::select()->orderBy('name', 'ASC')->all();
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
