<?php

namespace App\Controllers\Admin;

use App\Helpers\Report;
use Framework\Http\Request;
use App\Requests\UpdateUser;
use Framework\System\Session;
use App\Requests\RegisterUser;
use Framework\Routing\Controller;
use App\Database\Repositories\Roles;
use App\Database\Repositories\Users;
use Exception;

class UsersController extends Controller
{
    /**
     * @var \App\Database\Repositories\Users $users
     */
    private $users;
    
    /**
     * __construct
     *
     * @param  \App\Database\Repositories\Users $users
     * @return void
     */
    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        $data = $this->users->findAllPaginate();
        $active_users = $this->users->activeCount();
        $this->render('admin.users.index', compact('data', 'active_users'));
    }

	/**
	 * new
	 * 
	 * @return void
	 */
	public function new(): void
	{
		$this->render('admin.users.new');
	}
	
	/**
	 * edit
	 * 
     * @param  \App\Database\Repositories\Roles $roles
	 * @param  int $id
	 * @return void
	 */
	public function edit(Roles $roles, int $id): void
	{
        $data = $this->users->findSingle($id);
        $roles = $roles->selectAll();
        $this->render('admin.users.edit', compact('data', 'roles'));
	}
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function read(int $id): void
	{
        $data = $this->users->findSingle($id);
        $this->render('admin.users.read', compact('data'));
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
        
        if ($this->users->findBy('email', $request->email)->exists()) {
            redirect()->back()->withInputs($validator->inputs())->withToast('error', __('user_already_exists'))->go();
        }
        
        if ($this->users->findBy('phone', $request->phone)->exists()) {
            redirect()->back()->withInputs($validator->inputs())->withToast('error', __('user_already_exists'))->go();
        }

	    $id = $this->users->store($request);

        $this->log(__('user_created'));
        redirect()->route('users.read', $id)->withToast('success', __('user_created'))->go();
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
        
        $user = $this->users->findSingle($id);

        if ($user->email !== $request->email) {
            if ($this->users->findBy('email', $request->email)->exists()) {
                redirect()->back()->withInputs($validator->inputs())->withToast('error', __('user_already_exists'))->go();
            }
        }

        if ($user->phone !== $request->phone) {
            if ($this->users->findBy('phone', $request->phone)->exists()) {
                redirect()->back()->withInputs($validator->inputs())->withToast('error', __('user_already_exists'))->go();
            }
        }

        $this->users->refresh($request, $id);
		
        $this->log(__('user_updated'));
        redirect()->route('users.read', $id)->withToast('success', __('user_updated'))->go();
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
        $this->users->flush($request, $id);

		if (!is_null($id)) {
            $this->log(__('user_deleted'));
            redirect()->route('users.index')->withToast('success', __('user_deleted'))->go();
		} else {
            $this->log(__('users_deleted'));
            $this->toast('success', __('users_deleted'));
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
		$data = $this->users->findAllDateRange($request->date_start, $request->date_end);
        $filename = 'users_' . date('Y_m_d_His') . '.' . $request->file_type;

        $this->log(__('data_exported'));
        
		Report::generate($filename, $data, [
			'name' => __('name'), 
            'email' => __('email'),
            'phone' => __('phone'),
            'company' => __('company'),
			'role' => __('role'), 
			'created_at' => __('created_at')
		]);
	}
}
