<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Requests\UpdateUser;
use App\Helpers\ReportHelper;
use App\Requests\RegisterUser;
use Framework\Support\Session;
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
        $this->render('admin/resources/users/index', [
            'users' => UsersModel::select()->orderAsc('name')->paginate(20),
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
			$this->toast(__('user_not_found'))->error();
			$this->redirectBack()->only();
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
            $this->redirectBack()->withError($validator->errors());
        }

		if (
            UsersModel::select()
                ->where('email', $this->request->email)
                ->orWhere('phone', $this->request->phone)
                ->exists()
        ) {
			$this->toast(__('user_already_exists'))->error();
            $this->redirectBack()->only();
		}

	    $id = UsersModel::insert([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'phone' => $this->request->phone,
            'company' => $this->request->company,
            'password' => Encryption::encrypt($this->request->password),
            'role' => $this->request->role
		]);

		$this->toast(__('user_created'))->success();
		$this->redirect('admin/resources/users/view', $id)->only();
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
			$this->toast(__('user_not_found'))->error();
			$this->redirectBack()->only();
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
            $this->redirectBack()->withError($validator->errors());
        }

        $user = UsersModel::find('id', $id)->single();

		if ($user === false) {
			$this->toast(__('user_not_found'))->error();
			$this->redirectBack()->only();
        }

        if ($user->email !== $this->request->email || $user->phone !== $this->request->phone) {
            if (
                UsersModel::select()
                    ->where('email', $this->request->email)
                    ->orWhere('phone', $this->request->phone)
                    ->exists()
            ) {
                $this->toast(__('user_already_exists'))->error();
                $this->redirectBack()->only();
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
        
        if (Session::getUser()->id === $id) {
            Session::setUser($user);
        }

		$this->toast(__('user_updated'))->success();
        $this->redirect('admin/resources/users/view', $id)->only();
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
				$this->toast(__('user_not_found'))->error();
			}
	
			UsersModel::delete()->where('id', $id)->persist();
			$this->toast(__('user_deleted'))->success();
		} else {
            $users_id = explode(',', $this->request->items);

			foreach ($users_id as $id) {
				UsersModel::delete()->where('id', $id)->persist();
			}
			
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
			$this->toast(__('import_file_type_error'))->error();
            $this->redirectBack()->only();
		}

		if (!$file->isUploaded()) {
			$this->toast(__('import_data_error'))->error();
			$this->redirectBack()->only();
		}

		ReportHelper::import($file->getTempFilename(), UsersModel::class, [
			'name' => __('name'), 
            'email' => __('email'), 
            'phone' => __('phone'),
            'company' => __('company'),
			'password' => __('password')
		]);

		$this->toast(__('data_imported'))->success();
		$this->redirectBack()->only();
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
                ->between('created_at', Carbon::parse($this->request->date_start)->toDateTimeString(), Carbon::parse($this->request->date_end)->toDateTimeString())
                ->orderAsc('name')
                ->all();
		} else {
			$users = UsersModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'roles_' . date('Y_m_d') . '.' . $this->request->file_type;

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
