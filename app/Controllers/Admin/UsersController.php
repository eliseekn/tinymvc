<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\DateHelper;
use App\Requests\UpdateUser;
use App\Helpers\ReportHelper;
use App\Requests\RegisterUser;
use Framework\Support\Session;
use App\Helpers\Activity;
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
        $users = UsersModel::select()
            ->where('id', '!=', Auth::user()->id)
            ->orderAsc('name')
            ->paginate(20);

        $active_users = UsersModel::select()
            ->where('active', 1)
            ->andWhere('id', '!=', Auth::user()->id)
            ->all();

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
			$this->redirectBack()->withToast(__('user_not_found'))->error();
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
            $this->redirectBack()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withToast(__('user_not_created', true))->error();
        }

		if (
            UsersModel::select()
                ->where('email', $this->request->email)
                ->orWhere('phone', $this->request->phone)
                ->exists()
        ) {
            $this->redirectBack()->withInputs($validator->inputs())
                ->withToast(__('user_already_exists'))->error();
		}

	    $id = UsersModel::insert([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'phone' => $this->request->phone,
            'company' => $this->request->company,
            'password' => Encryption::hash($this->request->password)
		]);

        Activity::log('User created');
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
		$user = UsersModel::find('id', $id)->single();
		
		if ($user === false) {
			$this->redirectBack()->withToast(__('user_not_found'))->error();
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
            $this->redirectBack()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withToast(__('user_not_updated', true))->error();
        }

        $user = UsersModel::find('id', $id)->single();

		if ($user === false) {
            $this->redirectBack()->withInputs($validator->inputs())
                ->withToast(__('user_not_found'))->error();
        }

        if ($user->email !== $this->request->email || $user->phone !== $this->request->phone) {
            if (
                UsersModel::select()
                    ->where('email', $this->request->email)
                    ->orWhere('phone', $this->request->phone)
                    ->exists()
            ) {
                $this->redirectBack()->withInputs($validator->inputs())
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

        $user = UsersModel::find('id', $id)->single();
        
        if (Session::get('user')->id === $id) {
            Session::create('user', $user);
        }

        Activity::log('User updated');
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
			if (!UsersModel::find('id', $id)->exists()) {
				$this->redirectBack()->withToast(__('user_not_found'))->error();
			}
	
			UsersModel::delete()->where('id', $id)->persist();
            Activity::log('User deleted');
            $this->redirectBack()->withToast(__('user_deleted'))->success();
		} else {
            $users_id = explode(',', $this->request->items);

			foreach ($users_id as $id) {
				UsersModel::delete()->where('id', $id)->persist();
			}
			
            Activity::log('Users deleted');
            $this->toast(__('users_deleted'))->success();
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
            $this->redirectBack()->withToast(__('import_file_type_error'))->success();
		}

		if (!$file->isUploaded()) {
			$this->redirectBack()->withToast(__('import_data_error'))->error();
		}

		ReportHelper::import($file->getTempFilename(), UsersModel::class, [
			'name' => __('name'), 
            'email' => __('email'), 
            'phone' => __('phone'),
            'company' => __('company'),
			'password' => __('password')
		]);

        Activity::log('Users imported');
		$this->redirectBack()->withToast(__('data_imported'))->success();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
		$date_start = $this->request->has('date_start') ? $this->request->date_start : null;
        $date_end = $this->request->has('date_end') ? $this->request->date_end : null;

		if (!is_null($date_start) && !is_null($date_end)) {
			$users = UsersModel::select()
                ->between('created_at', DateHelper::format($date_start)->dateOnly(), DateHelper::format($date_end)->dateOnly())
                ->orderDesc('created_at')
                ->all();
		} else {
			$users = UsersModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'users_' . date('Y_m_d') . '.' . $this->request->file_type;

        Activity::log('Users exported');
        
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
