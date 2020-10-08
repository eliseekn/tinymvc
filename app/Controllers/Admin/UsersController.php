<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use App\Helpers\ReportHelper;
use Framework\Support\Session;
use App\Requests\RegisterRequest;
use Framework\Support\Encryption;
use App\Database\Models\RolesModel;
use App\Database\Models\UsersModel;
use App\Requests\UpdateUserRequest;

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
			'roles' => RolesModel::findAll()
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
		if (!UsersModel::has('id', $id)) {
			Session::flash('This user does not exists')->error()->toast();
			Redirect::back()->only();
		}

		View::render('admin/users/edit', [
			'user' => UsersModel::find($id),
			'roles' => RolesModel::findAll()
		]);
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
            Redirect::back()->withError($validate);
        }

		if (UsersModel::has('email', Request::getField('email'))) {
			Session::flash('This email address already exists')->error()->toast();
            Redirect::back()->only();
		}

	   $id = UsersModel::create([
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'password' => hash_string(Request::getField('password')),
			'role' => Request::getField('role')
		]);

		Session::flash('The user has been created successfully')->success()->toast();
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
		if (!UsersModel::has('id', $id)) {
			Session::flash('This user does not exists')->error()->toast();
			Redirect::back()->only();
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

		if (!UsersModel::has('id', $id)) {
			Session::flash('This user does not exists')->error()->toast();
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

		UsersModel::update($id, $data);

		Session::flash('The user has been updated successfully')->success()->toast();
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
			if (!UsersModel::has('id', $id)) {
				Session::flash('This user does not exists')->error()->toast();
			}
	
			UsersModel::delete($id);
			Session::flash('The user has been deleted successfully')->success()->toast();
		} else {
			$users_id = json_decode(Request::getRawData(), true);
			$users_id = $users_id['items'];

			foreach ($users_id as $id) {
				UsersModel::delete($id);
			}
			
			Session::flash('The selected users have been deleted successfully')->success()->toast();
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
			Session::flash('Only file of type extension ".csv" are allowed')->error()->toast();
            Redirect::back()->only();
		}

		if (!$file->isUploaded()) {
			Session::flash('Failed to import users data')->error()->toast();
			Redirect::back()->only();
		}

		$function = ['App\Database\Models\UsersModel', 'create'];

		ReportHelper::import($file->getTempFilename(), $function, [
			'name' => 'Name', 
			'email' => 'Email', 
			'password' => 'Password'
		]);

		Session::flash('The user has been imported successfully')->success()->toast();
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
