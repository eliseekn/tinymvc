<?php

namespace App\Controllers\Admin;

use App\Helpers\Activity;
use App\Helpers\DateHelper;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
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
		$role = RolesModel::find($id)->single();

		if ($role === false) {
			Redirect::back()->withToast(__('role_not_found'))->error();
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
            Response::json(['redirect' => absolute_url('admin/resources/roles/new')]);
        }

		$slug = slugify($this->request->title);

		if (RolesModel::findBy('slug', $slug)->exists()) {
			Alert::toast(__('role_already_exists'))->error();
            Session::create('inputs', $this->request->only('title', 'editor'));
            Response::json(['redirect' => absolute_url('admin/resources/roles/new')]);
		}

	    $id = RolesModel::insert([
            'title' => $this->request->title,
            'slug' => $slug,
            'description' => $this->request->editor
		]);

		Alert::toast(__('role_created'))->success();
        Activity::log('Role created');
        Response::json(['redirect' => absolute_url('admin/resources/roles/view/' . $id)]);
    }
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		$role = RolesModel::find($id)->single();

		if ($role === false) {
			Redirect::back()->withToast(__('role_not_found'))->error();
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
            Response::json(['redirect' => absolute_url('admin/resources/roles/edit/' . $id)]);
        }

        $role = RolesModel::find($id)->single();

		if ($role === false) {
			Alert::toast(__('role_not_found'))->error();
            Session::create('inputs', $this->request->only('title', 'editor'));
			Response::json(['redirect' => absolute_url('admin/resources/roles/edit/' . $id)]);
        }
        
        $slug = slugify($this->request->title);

		if ($role->slug !== $slug) {
            if (RolesModel::findBy('slug', $slug)->exists()) {
                Alert::toast(__('role_already_exists'))->error();
                Session::create('inputs', $this->request->only('title', 'editor'));
                Response::json(['redirect' => absolute_url('admin/resources/roles/edit/' . $id)]);
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

        Alert::toast(__('role_updated'))->success();
        Activity::log('Role updated');
		Response::json(['redirect' => absolute_url('admin/resources/roles/view/' . $id)]);
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
				Redirect::back()->withToast(__('role_not_found'))->error();
			}
	
			RolesModel::deleteWhere('id', $id);
            Activity::log('Role deleted');
            Redirect::url('admin/resources/roles')->withToast(__('role_deleted'))->success();
		} else {
			$roles_id = explode(',', $this->request->items);

			foreach ($roles_id as $id) {
				RolesModel::deleteWhere('id', $id);
			}
			
            Activity::log('Roles deleted');
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
            Redirect::url('admin/resources/roles')->withToast(__('import_file_type_error'))->success();
		}

		if (!$file->isUploaded()) {
			Redirect::url('admin/resources/roles')->withToast(__('import_data_error'))->error();
		}

		ReportHelper::import($file->getTempFilename(), RolesModel::class, [
			'title' => __('title'), 
			'slug' => __('slug'), 
			'description' => __('description')
		]);

        Activity::log('Roles imported');
        Redirect::url('admin/resources/roles')->withToast(__('data_imported'))->success();
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
                ->whereBetween('created_at', $date_start, $date_end)
                ->orderDesc('created_at')
                ->all();
		} else {
			$roles = RolesModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'roles_' . date('Y_m_d') . '.' . $this->request->file_type;

        Activity::log('Roles exported');

		ReportHelper::export($filename, $roles, [
			'title' => __('title'), 
			'slug' => __('slug'), 
			'description' => __('description'),
			'created_at' => __('created_at')
		]);
	}
}
