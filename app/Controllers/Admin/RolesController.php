<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use Framework\Support\Alert;
use App\Helpers\ReportHelper;
use App\Requests\RoleRequest;
use App\Database\Models\RolesModel;

class RolesController
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
    {
        View::render('admin/roles/index', [
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
		View::render('admin/roles/new');
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
			Alert::toast(__('role_not_found'))->error();
			Redirect::back()->only();
		}

		View::render('admin/roles/edit', compact('role'));
	}

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
		$validate = RoleRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
			Alert::default($validate)->error();
            Response::sendJson([], ['redirect' => absolute_url('admin/roles/new')]);
        }

		$slug = slugify(Request::getField('title'));

		if (RolesModel::find('slug', $slug)->exists()) {
			Alert::toast(__('role_already_exists'))->error();
			Response::sendJson([], ['redirect' => absolute_url('admin/roles/new')]);
		}

	    $id = RolesModel::insert([
            'title' => Request::getField('title'),
            'slug' => $slug,
            'description' => Request::getField('editor')
		]);

		Alert::toast(__('role_created'))->success();
		Response::sendJson([], ['redirect' => absolute_url('admin/roles/view/' . $id)]);
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
			Alert::toast(__('role_not_found'))->error();
			Redirect::back()->only();
		}

		View::render('admin/roles/view', compact('role'));
	}
    
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
		$validate = RoleRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
			Alert::default($validate)->error();
            Response::sendJson([], ['redirect' => absolute_url('admin/roles/edit')]);
        }

		if (!RolesModel::find('id', $id)->exists()) {
			Alert::toast(__('role_not_found'))->error();
			Response::sendJson([], ['redirect' => absolute_url('admin/roles/edit')]);
		}

		RolesModel::update([
            'title' => Request::getField('title'),
            'slug' => slugify(Request::getField('title')),
            'description' => Request::getField('editor')
		])
		->where('id', $id)
		->persist();

		Alert::toast(__('role_updated'))->success();
		Response::sendJson([], ['redirect' => absolute_url('admin/roles/view/' . $id)]);
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
				Alert::toast(__('role_not_found'))->error();
			}
	
			RolesModel::delete()->where('id', $id)->persist();
			Alert::toast(__('role_deleted'))->success();
            Redirect::back()->only();
		} else {
			$roles_id = json_decode(Request::getRawData(), true);
			$roles_id = $roles_id['items'];

			foreach ($roles_id as $id) {
				RolesModel::delete()->where('id', $id)->persist();
			}
			
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
        $file = Request::getFile('file', ['csv']);

		if (!$file->isAllowed()) {
			Alert::toast(__('import_file_type_error'))->error();
            Redirect::back()->only();
		}

		if (!$file->isUploaded()) {
			Alert::toast(__('import_data_error'))->error();
			Redirect::back()->only();
		}

		ReportHelper::import($file->getTempFilename(), RolesModel::class, [
			'title' => 'Title', 
			'slug' => 'Slug', 
			'description' => 'Description'
		]);

		Alert::toast(__('data_imported'))->success();
		Redirect::back()->only();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
        $date_start = Request::getField('date_start');
        $date_end = Request::getField('date_end');

		if (!empty($date_start) && !empty($date_end)) {
			$roles = RolesModel::select()
                ->between('created_at', Carbon::parse($date_start)->format('Y-m-d H:i:s'), Carbon::parse($date_end)->format('Y-m-d H:i:s'))
                ->orderBy('name', 'ASC')
                ->all();
		} else {
			$roles = RolesModel::select()->orderBy('name', 'ASC')->all();
        }
        
        $filename = 'roles_' . date('Y_m_d') . '.' . Request::getField('file_type');

		ReportHelper::export($filename, $roles, [
			'title' => 'Title', 
			'slug' => 'Slug', 
			'description' => 'Description',
			'created_at' => 'Created at'
		]);
	}
}
