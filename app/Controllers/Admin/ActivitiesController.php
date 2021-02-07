<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use Framework\Support\Alert;
use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use App\Database\Models\RolesModel;
use App\Database\Models\ActivitiesModel;

class ActivitiesController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
	{
        if (Auth::role(RolesModel::ROLE[0])) {
            $activities = ActivitiesModel::select();
        } else {
            $activities = ActivitiesModel::findBy('user', Auth::get()->email);
        }

		$this->render('admin/activities', [
            'activities' => $activities->orderDesc('created_at')->paginate(20)
        ]);
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
			if (!ActivitiesModel::find($id)->exists()) {
                $this->redirect()->withToast(__('activity_not_found'))->error();
			}
	
			ActivitiesModel::deleteWhere('id', $id);
            $this->redirect()->withToast(__('activity_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				ActivitiesModel::deleteWhere('id', $id);
			}
			
			Alert::toast(__('activities_deleted'))->success();
		}
	}

	/**
	 * export data
	 *
	 * @return void
	 */
    public function export(): void
	{
        if ($this->request->has('date_start') && $this->request->has('date_end')) {
			$activities = ActivitiesModel::select()
                ->whereBetween('created_at', $this->request->date_start, $this->request->date_end)
                ->orderDesc('created_at')
                ->all();
		} else {
			$activities = ActivitiesModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'activities_' . date('Y_m_d') . '.' . $this->request->file_type;

		ReportHelper::export($filename, $activities, [
			'user' => __('user'), 
			'url' => __('url'), 
			'method' => __('method'), 
			'ip_address' => __('ip_address'), 
			'action' => __('action'), 
			'target' => __('target'), 
			'created_at' => __('created_at')
		]);
	}
}
