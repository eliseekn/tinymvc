<?php

namespace App\Controllers\Admin;

use App\Helpers\DateHelper;
use App\Helpers\ReportHelper;
use App\Helpers\ActivityHelper;
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
		$role = RolesModel::find('id', $id)->single();

		if ($role === false) {
			$this->redirectBack()->withToast(__('role_not_found'))->error();
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
        $validator = Validator::validate((array) $this->request->only('title'), ['title' => 'required|alpha_space|max_len,255']);

        if ($validator->fails()) {
            $this->session('errors', $validator->errors());
            $this->session('inputs', (array) $this->request->only('title', 'editor'));
            $this->toast(__('role_not_created'))->error();
            $this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/new')]);
        }

		$slug = slugify($this->request->title);

		if (RolesModel::find('slug', $slug)->exists()) {
			$this->toast(__('role_already_exists'))->error();
            $this->session('inputs', (array) $this->request->only('title', 'editor'));
            $this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/new')]);
		}

	    $id = RolesModel::insert([
            'title' => $this->request->title,
            'slug' => $slug,
            'description' => $this->request->editor
		]);

		$this->toast(__('role_created'))->success();
        ActivityHelper::log('Role created');
        $this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/view/' . $id)]);
    }
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		$role = RolesModel::find('id', $id)->single();

		if ($role === false) {
			$this->redirectBack()->withToast(__('role_not_found'))->error();
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
        $validator = Validator::validate((array) $this->request->only('title'), ['title' => 'required|alpha_space|max_len,255']);
        
        if ($validator->fails()) {
            $this->toast(__('role_not_updated'))->error();
            $this->session('errors', $validator->errors());
            $this->session('inputs', (array) $this->request->only('title', 'editor'));
            $this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/edit/' . $id)]);
        }

        $role = RolesModel::find('id', $id)->single();

		if ($role === false) {
			$this->toast(__('role_not_found'))->error();
            $this->session('inputs', (array) $this->request->only('title', 'editor'));
			$this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/edit/' . $id)]);
        }
        
        $slug = slugify($this->request->title);

		if ($role->slug !== $slug) {
            if (RolesModel::find('slug', $slug)->exists()) {
                $this->toast(__('role_already_exists'))->error();
                $this->session('inputs', (array) $this->request->only('title', 'editor'));
                $this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/edit/' . $id)]);
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

        $this->toast(__('role_updated'))->success();
        ActivityHelper::log('Role updated');
		$this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/view/' . $id)]);
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
			if (!RolesModel::find('id', $id)->exists()) {
				$this->redirectBack()->withToast(__('role_not_found'))->error();
			}
	
			RolesModel::delete()->where('id', $id)->persist();
            ActivityHelper::log('Role deleted');
            $this->redirectBack()->withToast(__('role_deleted'))->success();
		} else {
			$roles_id = explode(',', $this->request->items);

			foreach ($roles_id as $id) {
				RolesModel::delete()->where('id', $id)->persist();
			}
			
            ActivityHelper::log('Roles deleted');
			$this->toast(__('roles_deleted'))->success();
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

		ReportHelper::import($file->getTempFilename(), RolesModel::class, [
			'title' => __('title'), 
			'slug' => __('slug'), 
			'description' => __('description')
		]);

        ActivityHelper::log('Roles imported');
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
			$roles = RolesModel::select()
                ->between('created_at', DateHelper::format($date_start)->dateOnly(), DateHelper::format($date_end)->dateOnly())
                ->orderDesc('created_at')
                ->all();
		} else {
			$roles = RolesModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'roles_' . date('Y_m_d') . '.' . $this->request->file_type;

        ActivityHelper::log('Roles exported');

		ReportHelper::export($filename, $roles, [
			'title' => __('title'), 
			'slug' => __('slug'), 
			'description' => __('description'),
			'created_at' => __('created_at')
		]);
	}
}
