<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use App\Helpers\ReportHelper;
use App\Requests\RoleRequest;
use App\Database\Models\RolesModel;
use Framework\Support\Notification;

class RolesController
{
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
			Notification::toast('This role does not exists', 'Role not exists')->error();
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
			Notification::alert($validate)->error();
            Response::sendJson([], ['redirect' => absolute_url('admin/roles/new')]);
        }

		$slug = slugify(Request::getField('title'));

		if (RolesModel::find('slug', $slug)->exists()) {
			Notification::toast('This role already exists', 'Role not created')->error();
			Response::sendJson([], ['redirect' => absolute_url('admin/roles/new')]);
		}

	   $id = RolesModel::insert([
            'title' => Request::getField('title'),
            'slug' => $slug,
            'description' => Request::getField('editor')
		]);

		Notification::toast('The role has been created successfully', 'Role created')->success();
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
			Notification::toast('This role does not exists', 'Role not exists')->error();
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
			Notification::alert($validate)->error();
            Response::sendJson([], ['redirect' => absolute_url('admin/roles/edit')]);
        }

		if (!RolesModel::find('id', $id)->exists()) {
			Notification::toast('This role does not exists', 'Role not exists')->error();
			Response::sendJson([], ['redirect' => absolute_url('admin/roles/edit')]);
		}

		RolesModel::update([
            'title' => Request::getField('title'),
            'slug' => slugify(Request::getField('title')),
            'description' => Request::getField('editor'),
            'updated_at' => date("Y-m-d H:i:s")
		])
		->where('id', '=', $id)
		->persist();

		Notification::toast('The role has been updated successfully', 'Role updated')->success();
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
				Notification::toast('This role does not exists', 'Role not exists')->error();
			}
	
			RolesModel::delete()->where('id', '=', $id)->persist();
			Notification::toast('The role has been deleted successfully', 'Role deleted')->success();
		} else {
			$roles_id = json_decode(Request::getRawData(), true);
			$roles_id = $roles_id['items'];

			foreach ($roles_id as $id) {
				RolesModel::delete()->where('id', '=', $id)->persist();
			}
			
			Notification::toast('The selected roles have been deleted successfully', 'Roles deleted')->success();
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
			Notification::toast('Only file of type extension .csv are allowed', 'File type error')->error();
            Redirect::back()->only();
		}

		if (!$file->isUploaded()) {
			Notification::toast('Failed to import roles data', 'Roles not imported')->error();
			Redirect::back()->only();
		}

		$function = ['App\Database\Models\RolesModel', 'create'];

		ReportHelper::import($file->getTempFilename(), $function, [
			'title' => 'Title', 
			'slug' => 'Slug', 
			'description' => 'Description'
		]);

		Notification::toast('The roles have been imported successfully', 'Roles imported')->success();
		Redirect::back()->only();
	}
	
	/**
	 * export data
	 *
	 * @return void
	 */
	public function export(): void
	{
		if (!empty(Request::getField('date_start')) && !empty(Request::getField('date_end'))) {
			$roles = RolesModel::findDateRange(Request::getField('date_start'), Request::getField('date_end'));
			$filename = 'roles_' . str_replace('-', '_', Request::getField('date_start')) . '-' . str_replace('-', '_', Request::getField('date_end')) . '.' . Request::getField('file_type');
		} else {
			$roles = RolesModel::select()->orderBy('name', 'ASC')->all();
			$filename = 'roles_' . date('Y_m_d') . '.' . Request::getField('file_type');
		}

		ReportHelper::export($filename, $roles, [
			'title' => 'Title', 
			'slug' => 'Slug', 
			'description' => 'Description',
			'created_at' => 'Created at'
		]);
	}
}
