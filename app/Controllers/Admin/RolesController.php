<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use App\Helpers\ReportHelper;
use Framework\Support\Session;
use App\Database\Models\RolesModel;
use App\Requests\RoleRequest;

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
		if (!RolesModel::has('id', $id)) {
			Session::flash('This role does not exists')->error()->toast();
			Redirect::back()->only();
		}

		View::render('admin/roles/edit', [
			'role' => RolesModel::find($id)
		]);
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
            Redirect::back()->withError($validate);
        }

		$slug = slugify(Request::getField('title'));

		if (RolesModel::has('slug', $slug)) {
			Session::flash('This role does not exists')->error()->toast();
			Redirect::back()->only();
		}

	   $id = RolesModel::create([
            'title' => Request::getField('title'),
            'slug' => $slug,
            'description' => Request::getField('editor')
		]);

		Session::flash('The role has been created successfully')->success()->toast();
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
		if (!RolesModel::has('id', $id)) {
			Session::flash('This role does not exists')->error()->toast();
			Redirect::back()->only();
		}

		View::render('admin/roles/view', [
			'role' => RolesModel::find($id)
		]);
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
            Redirect::back()->withError($validate);
        }

		if (!RolesModel::has('id', $id)) {
			Session::flash('This role does not exists')->error()->toast();
			Redirect::back()->only();
		}

		RolesModel::update($id, [
            'title' => Request::getField('title'),
            'slug' => slugify(Request::getField('title')),
            'description' => Request::getField('editor'),
            'updated_at' => date("Y-m-d H:i:s")
		]);

		Session::flash('The role has been updated successfully')->success()->toast();
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
			if (!RolesModel::has('id', $id)) {
				Session::flash('This role does not exists')->error()->toast();
			}
	
			RolesModel::delete($id);
			Session::flash('The role has been deleted successfully')->success()->toast();
		} else {
			$roles_id = json_decode(Request::getRawData(), true);
			$roles_id = $roles_id['items'];

			foreach ($roles_id as $id) {
				RolesModel::delete($id);
			}
			
			Session::flash('The selected roles have been deleted successfully')->success()->toast();
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
			Session::flash('Only file of type extension ".csv" are allowed')->error()->toast();
            Redirect::back()->only();
		}

		if (!$file->isUploaded()) {
			Session::flash('Failed to import roles data')->error()->toast();
			Redirect::back()->only();
		}

		$function = ['App\Database\Models\RolesModel', 'create'];

		ReportHelper::import($file->getTempFilename(), $function, [
			'title' => 'Title', 
			'slug' => 'Slug', 
			'description' => 'Description'
		]);

		Session::flash('The role has been imported successfully')->success()->toast();
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
			$roles = RolesModel::findAll(['name', 'ASC']);
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
