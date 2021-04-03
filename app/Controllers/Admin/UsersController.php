<?php

namespace App\Controllers\Admin;

use Exception;
use App\Requests\UpdateUser;
use App\Helpers\ReportHelper;
use App\Database\Models\Users;
use App\Requests\RegisterUser;
use Framework\Routing\Controller;

class UsersController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        $users = Users::paginate();
        $active_users = Users::activeCount();
        $this->render('admin.users.index', compact('users', 'active_users'));
    }

	/**
	 * new
	 * 
	 * @return void
	 */
	public function new(): void
	{
        $roles = $this->model('roles')->selectAll();
		$this->render('admin.users.new', compact('roles'));
	}
	
	/**
	 * edit
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function edit(int $id): void
	{
        try {
            $user = $this->model('users')->findOrFail('id', $id);
            $roles = $this->model('roles')->selectAll();
            $this->render('admin.users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            $this->redirect('admin/resources/users')->withToast(__('user_not_found'))->error();
        }
	}
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function read(int $id): void
	{
        try {
            $user = $this->model('users')->findOrFail('id', $id);
            $this->render('admin.users.read', compact('user'));
        } catch (Exception $e) {
            $this->redirect('admin/resources/users')->withToast(__('user_not_found'))->error();
        }
	}

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
        $validator = RegisterUser::validate($this->request()->inputs())->redirectOnFail();
        
        if ($this->model('users')->findBy('email', $this->request('email'))->exists()) {
            $this->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
        }
        
        if ($this->model('users')->findBy('phone', $this->request('phone'))->exists()) {
            $this->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
        }

	    $id = Users::store($this->request());

        $this->log(__('user_created'));
        $this->redirect('admin/resources/users/read', $id)->withToast(__('user_created'))->success();
    }
    
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
		$validator = UpdateUser::validate($this->request()->inputs())->redirectOnFail();
        
        try {
            $user = $this->model('users')->findOrFail('id', $id);

            if ($user->email !== $this->request('email')) {
                if ($this->model('users')->findBy('email', $this->request('email'))->exists()) {
                    $this->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
                }
            }

            if ($user->phone !== $this->request('phone')) {
                if ($this->model('users')->findBy('phone', $this->request('phone'))->exists()) {
                    $this->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
                }
            }
        } catch (Exception $e) {
            $this->back()->withInputs($validator->inputs())->withToast(__('user_not_found'))->error();
        }

        Users::update($this->request(), $id);
		
        $this->log(__('user_updated'));
        $this->redirect('admin/resources/users/read', $id)->withToast(__('user_updated'))->success();
    }

	/**
	 * delete
	 *
     * @param  int|null $id
	 * @return void
	 */
	public function delete(?int $id = null): void
	{
        Users::delete($this->request(), $id);

		if (!is_null($id)) {
            $this->log(__('user_deleted'));
            $this->redirect('admin/resources/users')->withToast(__('user_deleted'))->success();
		} else {
            $this->log(__('users_deleted'));
            $this->alert('toast', __('users_deleted'))->success();
            $this->response(['redirect' => absolute_url('admin/resources/users')], true);
        }
	}

	/**
	 * export
	 *
	 * @return void
	 */
	public function export(): void
	{
		$users = $this->model('users')
            ->between($this->request('date_start'), $this->request('date_end'))
            ->oldest()
            ->all();
        
        $filename = 'users_' . date('Y_m_d_His') . '.' . $this->request('file_type');

        $this->log(__('data_exported'));
        
		ReportHelper::export($filename, $users, [
			'name' => __('name'), 
            'email' => __('email'),
            'phone' => __('phone'),
            'company' => __('company'),
			'role' => __('role'), 
			'created_at' => __('created_at')
		]);
	}
}
