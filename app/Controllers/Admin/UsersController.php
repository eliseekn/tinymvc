<?php

namespace App\Controllers\Admin;

use Exception;
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
            ->orderDesc('created_at')
            ->paginate(20);

        $active_users = UsersModel::count()
            ->where('id', '!=', Auth::get()->id)
            ->and('active', 1)
            ->single()
            ->value;

        $this->render('admin.users.index', compact('users', 'active_users'));
    }

	/**
	 * display new page
	 * 
	 * @return void
	 */
	public function new(): void
	{
		$this->render('admin.users.new', ['roles' => RolesModel::selectAll()]);
	}
	
	/**
	 * display edit page
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function edit(int $id): void
	{
        try {
            $user = UsersModel::findOrFail('id', $id);
        } catch (Exception $e) {
            $this->redirect('admin/resources/users')->withToast(__('user_not_found'))->error();
        }

		$roles = RolesModel::selectAll();

		$this->render('admin.users.edit', compact('user', 'roles'));
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
            'password' => Encryption::hash($this->request->password),
            'active' => 1,
            'role' => RolesModel::ROLE[1]
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
        try {
            $user = UsersModel::findOrFail('id', $id);
        } catch (Exception $e) {
            $this->redirect('admin/resources/users')->withToast(__('user_not_found'))->error();
        }

		$this->render('admin.users.view', compact('user'));
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

        try {
            $user = UsersModel::findOrFail('id', $id);

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
        } catch (Exception $e) {
            $this->redirect()->withInputs($validator->inputs())
                ->withToast(__('user_not_found'))->error();
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
	
			UsersModel::deleteIfExists($id);
            Activity::log(__('user_deleted'));
            $this->redirect('admin/users')->withToast(__('user_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				UsersModel::deleteIfExists($id);
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
            $this->redirect('admin/users')->withToast(__('import_file_type_error') . 'csv')->success();
		}

		if (!$file->isUploaded()) {
			$this->redirect('admin/users')->withToast(__('import_data_error'))->error();
		}

		ReportHelper::import($file, UsersModel::class, [
			'name' => __('name'), 
            'email' => __('email'), 
            'phone' => __('phone'),
            'company' => __('company'),
			'role' => __('role'), 
			'password' => __('password')
		]);

        Activity::log(__('data_imported'));
		$this->redirect('admin/users')->withToast(__('data_imported'))->success();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
		$users = UsersModel::select()
            ->subQuery(function ($query) {
                if ($this->request->has('date_start') && $this->request->has('date_end')) {
                    $query->whereBetween('created_at', $this->request->date_start, $this->request->date_end);
                }
            })
            ->orderDesc('created_at')
            ->all();
        
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
