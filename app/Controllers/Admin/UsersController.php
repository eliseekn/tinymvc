<?php

namespace App\Controllers\Admin;

use Exception;
use App\Helpers\Auth;
use App\Requests\UpdateUser;
use App\Helpers\ReportHelper;
use App\Requests\RegisterUser;
use Framework\Routing\Controller;
use Framework\Support\Encryption;
use App\Database\Models\RolesModel;

class UsersController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        $users = $this->model('users')
            ->find('!=', Auth::get()->id)
            ->orderDesc('created_at')
            ->paginate(20);

        $active_users = $this->model('users')
            ->count()
            ->where('id', '!=', Auth::get()->id)
            ->and('active', 1)
            ->single()
            ->value;

        $this->render('admin.users.index', compact('users', 'active_users'));
    }

	/**
	 * new
	 * 
	 * @return void
	 */
	public function new(): void
	{
		$this->render('admin.users.new', ['roles' => $this->model('roles')->selectAll()]);
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
        $validator = RegisterUser::validate($this->request->inputs())->redirectOnFail();
        
		if ($this->model('users')->findMany($this->request->only('email', 'phone'))->exists()) {
            $this->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
		}

	    $id = $this->model('users')->insert([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'phone' => $this->request->phone,
            'company' => $this->request->company,
            'password' => Encryption::hash($this->request->password),
            'active' => 1,
            'role' => RolesModel::ROLE[1]
		]);

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
		$validator = UpdateUser::validate($this->request->inputs())->redirectOnFail();
        
        try {
            $user = $this->model('users')->findOrFail('id', $id);

            if ($user->email !== $this->request->email || $user->phone !== $this->request->phone) {
                if ($this->model('users')->findMany($this->request->only('email', 'phone'))->exists()) {
                    $this->back()->withInputs($validator->inputs())->withToast(__('user_already_exists'))->error();
                }
            }
        } catch (Exception $e) {
            $this->back()->withInputs($validator->inputs())->withToast(__('user_not_found'))->error();
        }

		$data = [
            'name' => $this->request->name,
            'email' => $this->request->email,
            'role' => $this->request->role,
            'phone' => $this->request->phone,
            'company' => $this->request->company,
            'active' => $this->request->account_state
		];
		
		if ($this->request->has('password')) {
			$data['password'] = Encryption::hash($this->request->password);
		}

        $this->model('users')->updateIfExists($id, $data);
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
		if (!is_null($id)) {
            if (!$this->model('users')->deleteIfExists($id)) {
                $this->back()->withToast(__('user_not_found'))->error();
			}
	
            $this->log(__('user_deleted'));
            $this->redirect('admin/resources/users')->withToast(__('user_deleted'))->success();
		}
        
        if (!$this->model('users')->deleteBy('id', 'in', explode(',', $this->request->items))) {
            $this->alert('toast', __('user_not_found'))->error();
        } else {
            $this->log(__('users_deleted'));
            $this->alert('toast', __('users_deleted'))->success();
        }

        $this->response([absolute_url('admin/resources/users')], true);
	}

	/**
	 * import
	 *
	 * @return void
	 */
	public function import(): void
	{
        $file = $this->request->files('file', ['csv']);

		if (!$file->isAllowed()) {
            $this->redirect('admin/resources/users')->withToast(__('import_file_type_error') . 'csv')->success();
		}

		if (!$file->isUploaded()) {
			$this->redirect('admin/resources/users')->withToast(__('import_data_error'))->error();
		}

		ReportHelper::import($file, $this->model('users')->class, [
			'name' => __('name'), 
            'email' => __('email'), 
            'phone' => __('phone'),
            'company' => __('company'),
			'role' => __('role'), 
			'password' => __('password')
		]);

        $this->log(__('data_imported'));
		$this->redirect('admin/resources/users')->withToast(__('data_imported'))->success();
	}
	
	/**
	 * export
	 *
	 * @return void
	 */
	public function export(): void
	{
		$users = $this->model('users')->select()
            ->subQuery(function ($query) {
                if ($this->request->has('date_start') && $this->request->has('date_end')) {
                    $query->whereBetween('created_at', $this->request->date_start, $this->request->date_end);
                }
            })
            ->orderDesc('created_at')
            ->all();
        
        $filename = 'users_' . date('Y_m_d') . '.' . $this->request->file_type;

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
