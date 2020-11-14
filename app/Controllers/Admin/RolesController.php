<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Helpers\ReportHelper;
use App\Requests\RoleRequest;
use Framework\Routing\Controller;
use App\Database\Models\RolesModel;

class RolesController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
    {
        $this->render('admin/roles/index', [
            'roles' => RolesModel::select()->orderAsc('title')->paginate(50)
        ]);
    }

	/**
	 * display new page
	 * 
	 * @return void
	 */
	public function new(): void
	{
		$this->render('admin/roles/new');
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
			$this->toast(__('role_not_found'))->error();
			$this->redirect()->only();
		}

		$this->render('admin/roles/edit', compact('role'));
	}

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
		$validate = RoleRequest::validate($this->request->inputs());
        
        if ($validate->fails()) {
			$this->alert($validate::$errors)->error();
            $this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/new')]);
        }

		$slug = slugify($this->request->title);

		if (RolesModel::find('slug', $slug)->exists()) {
			$this->toast(__('role_already_exists'))->error();
			$this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/new')]);
		}

	    $id = RolesModel::insert([
            'title' => $this->request->title,
            'slug' => $slug,
            'description' => $this->request->editor
		]);

		$this->toast(__('role_created'))->success();
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
			$this->toast(__('role_not_found'))->error();
			$this->redirect()->only();
		}

		$this->render('admin/roles/view', compact('role'));
	}
    
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
        $validate = RoleRequest::validate($this->request->inputs());
        
        if ($validate->fails()) {
			$this->alert($validate::$errors)->error();
            $this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/edit')]);
        }

		if (!RolesModel::find('id', $id)->exists()) {
			$this->toast(__('role_not_found'))->error();
			$this->jsonResponse(['redirect' => absolute_url('admin/resources/roles/edit')]);
		}

		RolesModel::update([
            'title' => $this->request->title,
            'slug' => slugify($this->request->title),
            'description' => $this->request->editor
		])
		->where('id', $id)
		->persist();

		$this->toast(__('role_updated'))->success();
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
				$this->toast(__('role_not_found'))->error();
			}
	
			RolesModel::delete()->where('id', $id)->persist();
			$this->toast(__('role_deleted'))->success();
            $this->redirect()->only();
		} else {
			$roles_id = json_decode($this->request->raw(), true);
			$roles_id = $roles_id['items'];

			foreach ($roles_id as $id) {
				RolesModel::delete()->where('id', $id)->persist();
			}
			
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
			$this->toast(__('import_file_type_error'))->error();
            $this->redirect()->only();
		}

		if (!$file->isUploaded()) {
			$this->toast(__('import_data_error'))->error();
			$this->redirect()->only();
		}

		ReportHelper::import($file->getTempFilename(), RolesModel::class, [
			'title' => __('title'), 
			'slug' => __('slug'), 
			'description' => __('description')
		]);

		$this->toast(__('data_imported'))->success();
		$this->redirect()->only();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
        $date_start = $this->request->date_start;
        $date_end = $this->request->date_end;

		if (!empty($date_start) && !empty($date_end)) {
			$roles = RolesModel::select()
                ->between('created_at', Carbon::parse($date_start)->toDateTimeString(), Carbon::parse($date_end)->toDateTimeString())
                ->orderAsc('name')
                ->all();
		} else {
			$roles = RolesModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'roles_' . date('Y_m_d') . '.' . $this->request->file_type;

		ReportHelper::export($filename, $roles, [
			'title' => __('title'), 
			'slug' => __('slug'), 
			'description' => __('description'),
			'created_at' => __('created_at')
		]);
	}
}
