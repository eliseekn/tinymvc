<?php

namespace App\Controllers\Admin;

use App\Helpers\Report;
use Framework\Http\Request;
use App\Requests\UpdateUser;
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
        $user = $this->model('users')->findSingle($id);
        $roles = $this->model('roles')->selectAll();
        $this->render('admin.users.edit', compact('user', 'roles'));
	}
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function read(int $id): void
	{
        $user = $this->model('users')->findSingle($id);
        $this->render('admin.users.read', compact('user'));
	}

	/**
	 * create
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function create(Request $request): void
	{
        $validator = RegisterUser::validate($request->inputs())->redirectOnFail();
        
        if ($this->model('users')->findBy('email', $request->email)->exists()) {
            redirect()->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
        }
        
        if ($this->model('users')->findBy('phone', $request->phone)->exists()) {
            redirect()->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
        }

	    $id = Users::store($request);

        $this->log(__('user_created'));
        redirect()->route('users.read', $id)->withToast(__('user_created'))->success();
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
		$validator = UpdateUser::validate($request->inputs())->redirectOnFail();
        
        $user = $this->model('users')->findSingle($id);

        if ($user->email !== $request->email) {
            if ($this->model('users')->findBy('email', $request->email)->exists()) {
                redirect()->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
            }
        }

        if ($user->phone !== $request->phone) {
            if ($this->model('users')->findBy('phone', $request->phone)->exists()) {
                redirect()->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
            }
        }

        Users::update($request, $id);
		
        $this->log(__('user_updated'));
        redirect()->route('users.read', $id)->withToast(__('user_updated'))->success();
    }

	/**
	 * delete
	 *
     * @param  \Framework\Http\Request $request
     * @param  int|null $id
	 * @return void
	 */
	public function delete(Request $request, ?int $id = null): void
	{
        Users::delete($request, $id);

		if (!is_null($id)) {
            $this->log(__('user_deleted'));
            redirect()->route('users.index')->withToast(__('user_deleted'))->success();
		} else {
            $this->log(__('users_deleted'));
            $this->alert('toast', __('users_deleted'))->success();
            response()->json(['redirect' => route('users.index')]);
        }
	}

	/**
	 * export
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function export(Request $request): void
	{
		$users = Users::fromDateRange($request->date_start, $request->date_end);
        $filename = 'users_' . date('Y_m_d_His') . '.' . $request->file_type;

        $this->log(__('data_exported'));
        
		Report::generate($filename, $users, [
			'name' => __('name'), 
            'email' => __('email'),
            'phone' => __('phone'),
            'company' => __('company'),
			'role' => __('role'), 
			'created_at' => __('created_at')
		]);
	}
}
