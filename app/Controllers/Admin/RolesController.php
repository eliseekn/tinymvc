<?php

namespace App\Controllers\Admin;

use App\Helpers\Activity;
use Framework\Support\Alert;
use App\Helpers\ReportHelper;
use Framework\Support\Session;
use Framework\Support\Validator;
use Framework\Routing\Controller;
use App\Database\Models\RolesModel;
use App\Database\Models\UsersModel;

class RolesController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
    {
        $this->render('admin/resources/roles/index', [
            'roles' => RolesModel::select()->orderAsc('title')->paginate(20)
        ]);
    }

	/**
	 * display new page
	 * 
	 * @return void
	 */
	public function new(): void
	{
		$this->render('admin/resources/roles/new');
	}
	
	/**
	 * display edit page
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function edit(int $id): void
	{
		$role = RolesModel::findSingle($id);

		if ($role === false) {
			$this->redirect()->withToast(__('role_not_found'))->error();
		}

		$this->render('admin/resources/roles/edit', compact('role'));
	}

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
        $validator = Validator::validate($this->request->only('title'), ['title' => 'required|alpha_space|max_len,255']);

        if ($validator->fails()) {
            Session::create('errors', $validator->errors());
            Session::create('inputs', $this->request->only('title', 'editor'));
            Alert::toast(__('role_not_created'))->error();
            $this->response(['redirect' => absolute_url('admin/resources/roles/new')], true);
        }

		$slug = slugify($this->request->title);

		if (RolesModel::findBy('slug', $slug)->exists()) {
			Alert::toast(__('role_already_exists'))->error();
            Session::create('inputs', $this->request->only('title', 'editor'));
            $this->response(['redirect' => absolute_url('admin/resources/roles/new')], true);
		}

	    $id = RolesModel::insert([
            'title' => $this->request->title,
            'slug' => $slug,
            'description' => $this->request->editor
		]);

        Activity::log(__('role_created'));
		Alert::toast(__('role_created'))->success();
        $this->response(['redirect' => absolute_url('admin/resources/roles/view', $id)], true);
    }
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		$role = RolesModel::findSingle($id);

		if ($role === false) {
			$this->redirect()->withToast(__('role_not_found'))->error();
		}

		$this->render('admin/resources/roles/view', compact('role'));
	}
    
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
        $validator = Validator::validate($this->request->only('title'), ['title' => 'required|alpha_space|max_len,255']);
        
        if ($validator->fails()) {
            Alert::toast(__('role_not_updated'))->error();
            Session::create('errors', $validator->errors());
            Session::create('inputs', $this->request->only('title', 'editor'));
            $this->response(['redirect' => absolute_url('admin/resources/roles/edit', $id)], true);
        }

        $role = RolesModel::find($id)->single();

		if ($role === false) {
			Alert::toast(__('role_not_found'))->error();
            Session::create('inputs', $this->request->only('title', 'editor'));
			$this->response(['redirect' => absolute_url('admin/resources/roles/edit', $id)], true);
        }
        
        $slug = slugify($this->request->title);

		if ($role->slug !== $slug) {
            if (RolesModel::findBy('slug', $slug)->exists()) {
                Alert::toast(__('role_already_exists'))->error();
                Session::create('inputs', $this->request->only('title', 'editor'));
                $this->response(['redirect' => absolute_url('admin/resources/roles/edit', $id)], true);
            }
        }

		RolesModel::update([
            'title' => $this->request->title,
            'slug' => $slug,
            'description' => $this->request->editor
		])
		->where('id', $id)
        ->persist();
        
        //also update role slug for users
        UsersModel::update(['role' => $slug])
            ->where('role', $role->slug)
            ->persist();

        Activity::log(__('role_updated'));
        Alert::toast(__('role_updated'))->success();
		$this->response(['redirect' => absolute_url('admin/resources/roles/view', $id)], true);
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
			if (!RolesModel::find($id)->exists()) {
				$this->redirect()->withToast(__('role_not_found'))->error();
			}
	
			RolesModel::deleteWhere('id', $id);
            Activity::log(__('role_deleted'));
            $this->redirect('admin/resources/roles')->withToast(__('role_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				RolesModel::deleteWhere('id', $id);
			}
			
            Activity::log(__('roles_deleted'));
			Alert::toast(__('roles_deleted'))->success();
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
            $this->redirect('admin/resources/roles')->withToast(__('import_file_type_error') . 'csv')->success();
		}

		if (!$file->isUploaded()) {
			$this->redirect('admin/resources/roles')->withToast(__('import_data_error'))->error();
		}

		ReportHelper::import($file->getTempFilename(), RolesModel::class, [
			'title' => __('title'), 
			'slug' => __('slug'), 
			'description' => __('description')
		]);

        Activity::log(__('data_imported'));
        $this->redirect('admin/resources/roles')->withToast(__('data_imported'))->success();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
        if ($this->request->has('date_start') && $this->request->has('date_end')) {
			$roles = RolesModel::select()
                ->whereBetween('created_at', $this->request->date_start, $this->request->date_end)
                ->orderDesc('created_at')
                ->all();
		} else {
			$roles = RolesModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'roles_' . date('Y_m_d') . '.' . $this->request->file_type;

        Activity::log(__('data_exported'));

		ReportHelper::export($filename, $roles, [
			'title' => __('title'), 
			'slug' => __('slug'), 
			'description' => __('description'),
			'created_at' => __('created_at')
		]);
	}
}
