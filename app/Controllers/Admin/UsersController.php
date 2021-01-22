<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\Activity;
use App\Requests\UpdateUser;
use Framework\Support\Alert;
use App\Helpers\ReportHelper;
use App\Requests\RegisterUser;
use Framework\Routing\Controller;
use Framework\Support\Encryption;
use App\Database\Models\RolesModel;
use App\Database\Models\UsersModel;

class UsersController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
    {
        $users = UsersModel::find('!=', Auth::get()->id)
            ->orderAsc('name')
            ->paginate(20);

        $active_users = UsersModel::count()
            ->where('id', '!=', Auth::get()->id)
            ->and('active', 1)
            ->single()
            ->value;

        $this->render('admin/resources/users/index', compact('users', 'active_users'));
    }

	/**
	 * display new page
	 * 
	 * @return void
	 */
	public function new(): void
	{
		$this->render('admin/resources/users/new', [
			'roles' => RolesModel::selectAll()
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
		$user = UsersModel::findSingle($id);
		$roles = RolesModel::selectAll();

		if ($user === false) {
			$this->redirect()->withToast(__('user_not_found'))->error();
		}

		$this->render('admin/resources/users/edit', compact('user', 'roles'));
	}

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
        $validator = RegisterUser::validate($this->request->inputs());
        
        if ($validator->fails()) {
            $this->redirect()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withToast(__('user_not_created'))->error();
        }

		if (
            UsersModel::findBy('email', $this->request->email)
                ->or('phone', $this->request->phone)
                ->exists()
        ) {
            $this->redirect()->withInputs($validator->inputs())
                ->withToast(__('user_already_exists'))->error();
		}

	    $id = UsersModel::insert([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'phone' => $this->request->phone,
            'company' => $this->request->company,
            'password' => Encryption::hash($this->request->password)
		]);

        Activity::log(__('user_created'));
        $this->redirect('admin/resources/users/view', $id)->withToast(__('user_created'))->success();
    }
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		$user = UsersModel::findSingle($id);
		
		if ($user === false) {
			$this->redirect()->withToast(__('user_not_found'))->error();
		}

		$this->render('admin/resources/users/view', compact('user'));
	}
    
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
		$validator = UpdateUser::validate($this->request->inputs());
        
        if ($validator->fails()) {
            $this->redirect()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withToast(__('user_not_updated', true))->error();
        }

        $user = UsersModel::find($id)->single();

		if ($user === false) {
            $this->redirect()->withInputs($validator->inputs())
                ->withToast(__('user_not_found'))->error();
        }

        if ($user->email !== $this->request->email || $user->phone !== $this->request->phone) {
            if (
                UsersModel::findBy('email', $this->request->email)
                    ->or('phone', $this->request->phone)
                    ->exists()
            ) {
                $this->redirect()->withInputs($validator->inputs())
                    ->withToast(__('user_already_exists'))->error();
            }
        }

		$data = [
            'name' => $this->request->name,
            'email' => $this->request->email,
            'role' => $this->request->role,
            'phone' => $this->request->phone,
            'company' => $this->request->company,
            'active' => $this->request->account_state
		];
		
		if (!empty($this->request->password)) {
			$data['password'] = Encryption::hash($this->request->password);
		}

        UsersModel::update($data)->where('id', $id)->persist();
        
        Activity::log(__('user_updated'));
        $this->redirect('admin/resources/users/view', $id)->withToast(__('user_updated'))->success();
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
			if (!UsersModel::find($id)->exists()) {
				$this->redirect()->withToast(__('user_not_found'))->error();
			}
	
			UsersModel::deleteWhere('id', $id);
            Activity::log(__('user_deleted'));
            $this->redirect('admin/resources/users')->withToast(__('user_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				UsersModel::deleteWhere('id', $id);
			}
			
            Activity::log(__('users_deleted'));
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
        $file = $this->request->files('file', ['csv']);

		if (!$file->isAllowed()) {
            $this->redirect('admin/resources/users')->withToast(__('import_file_type_error'))->success();
		}

		if (!$file->isUploaded()) {
			$this->redirect('admin/resources/users')->withToast(__('import_data_error'))->error();
		}

		ReportHelper::import($file->getTempFilename(), UsersModel::class, [
			'name' => __('name'), 
            'email' => __('email'), 
            'phone' => __('phone'),
            'company' => __('company'),
			'password' => __('password')
		]);

        Activity::log(__('data_imported'));
		$this->redirect('admin/resources/users')->withToast(__('data_imported'))->success();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
		if ($this->request->has('date_start') && $this->request->has('date_end')) {
			$users = UsersModel::select()
                ->whereBetween('created_at', $this->request->date_start, $this->request->date_end)
                ->orderDesc('created_at')
                ->all();
		} else {
			$users = UsersModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'users_' . date('Y_m_d') . '.' . $this->request->file_type;

        Activity::log(__('data_exported'));
        
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
