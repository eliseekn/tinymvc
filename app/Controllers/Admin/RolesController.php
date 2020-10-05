<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use App\Requests\CreateRoleRequest;
use App\Requests\UpdateRoleRequest;
use App\Database\Models\RolesModel;
use App\Helpers\ReportHelper;
use Framework\HTTP\Response;

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
		if (!RolesModel::exists('id', $id)) {
			Redirect::back()->withError('This role does not exists');
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
		$validate = CreateRoleRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
            create_flash_messages('danger', $validate);
        }

		$slug = slugify(Request::getField('title'));

		if (RolesModel::exists('slug', $slug)) {
			create_flash_messages('danger', 'This role does not exists');
		}

	   $id = RolesModel::create([
            'title' => Request::getField('title'),
            'slug' => $slug,
            'description' => Request::getField('editor')
		]);

		create_flash_messages('success', 'The role has been created successfully');
		Response::sendJson([], ['redirect' => absolute_url('/admin/roles/view/' . $id)]);
    }
	
	/**
	 * read
	 * 
	 * @param  int $id
	 * @return void
	 */
	public function view(int $id): void
	{
		if (!RolesModel::exists('id', $id)) {
			Redirect::back()->withError('This role does not exists');
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
		$validate = UpdateRoleRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
			create_flash_messages('danger', $validate);
        }

		if (!RolesModel::exists('id', $id)) {
			create_flash_messages('danger', 'This role does not exists');
		}

		RolesModel::update($id, [
            'title' => Request::getField('title'),
            'slug' => slugify(Request::getField('title')),
            'description' => Request::getField('editor'),
            'updated_at' => date("Y-m-d H:i:s")
		]);

		create_flash_messages('success', 'The role has been updated successfully');
		Response::sendJson([], ['redirect' => absolute_url('/admin/roles/view/' . $id)]);
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
			if (!RolesModel::exists('id', "$id")) {
				create_flash_messages('danger', 'This role does not exists');
			}
	
			RolesModel::delete($id);
			create_flash_messages('success', 'The role has been deleted successfully');
		} else {
			$roles_id = json_decode(Request::getRawData(), true);
			$roles_id = $roles_id['items'];

			foreach ($roles_id as $id) {
				RolesModel::delete($id);
			}
			
			create_flash_messages('success', 'The selected roles have been deleted successfully');
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
			Redirect::back()->withError('Only file of type extension ".csv" are allowed');
		}

		if (!$file->isUploaded()) {
			Redirect::back()->withError('Failed to import users data');
		}

		$function = ['App\Database\Models\RolesModel', 'create'];

		ReportHelper::import($file->getTempFilename(), $function, [
			'title' => 'Title', 
			'slug' => 'Slug', 
			'description' => 'Description'
		]);

		Redirect::back()->withSuccess('The users have been imported successfully');
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
