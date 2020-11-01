<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\Support\Alert;
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
     * display list
     *
     * @return void
     */
    public function index(): void
    {
        View::render('admin/users/index', [
            'users' => UsersModel::select()->orderAsc('name')->paginate(50),
            'online_users' => UsersModel::find('online', 1)->all(),
			'active_users' => UsersModel::find('active', 1)->all(),
        ]);
    }

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
			Alert::toast(__('user_not_found'))->error();
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
            Redirect::back()->withError($validate);
        }

		if (UsersModel::find('email', Request::getField('email'))->exists()) {
			Alert::toast(__('user_already_exists'))->error();
            Redirect::back()->only();
		}

	    $id = UsersModel::insert([
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'password' => hash_string(Request::getField('password')),
            'role' => Request::getField('role')
		]);

		Alert::toast(__('user_created'))->success();
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
			Alert::toast(__('user_not_found'))->error();
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
			Alert::toast(__('user_not_found'))->error();
			Redirect::back()->only();
		}

		$data = [
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'role' => Request::getField('role'),
            'active' => Request::getField('account_state')
		];
		
		if (!empty(Request::getField('password'))) {
			$data['password'] = Encryption::hash(Request::getField('password'));
		}

        UsersModel::update($data)->where('id', $id)->persist();

        $user = UsersModel::find('id', $id)->single();
        
        if (Session::getUser()->id === $id) {
            Session::setUser($user);
        }

		Alert::toast(__('user_updated'))->success();
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
				Alert::toast(__('user_not_found'))->error();
			}
	
			UsersModel::delete()->where('id', $id)->persist();
			Alert::toast(__('user_deleted'))->success();
            Redirect::back()->only();
		} else {
			$users_id = json_decode(Request::getRawData(), true);
			$users_id = $users_id['items'];

			foreach ($users_id as $id) {
				UsersModel::delete()->where('id', $id)->persist();
			}
			
			Alert::toast(__('users_deleted'))->success();
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
			Alert::toast(__('import_file_type_error'))->error();
            Redirect::back()->only();
		}

		if (!$file->isUploaded()) {
			Alert::toast(__('import_data_error'))->error();
			Redirect::back()->only();
		}

		ReportHelper::import($file->getTempFilename(), UsersModel::class, [
			'name' => 'Name', 
			'email' => 'Email', 
			'password' => 'Password'
		]);

		Alert::toast(__('data_imported'))->success();
		Redirect::back()->only();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
		$date_start = Request::getField('date_start');
        $date_end = Request::getField('date_end');

		if (!empty($date_start) && !empty($date_end)) {
			$users = UsersModel::select()
                ->between('created_at', Carbon::parse($date_start)->format('Y-m-d H:i:s'), Carbon::parse($date_end)->format('Y-m-d H:i:s'))
                ->orderBy('name', 'ASC')
                ->all();
		} else {
			$users = UsersModel::select()->orderBy('name', 'ASC')->all();
        }
        
        $filename = 'roles_' . date('Y_m_d') . '.' . Request::getField('file_type');

		ReportHelper::export($filename, $users, [
			'name' => 'Name', 
			'email' => 'Email', 
			'role' => 'Role', 
			'created_at' => 'Created at'
		]);
	}
}
