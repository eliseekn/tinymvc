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
        $activities = ActivitiesModel::select()
            ->subQuery(function ($query) {
                if (Auth::get()->role !== RolesModel::ROLE[0]) {
                    $query->where('user', Auth::get()->email);
                }
            })
            ->orderDesc('created_at')
            ->paginate(20);

		$this->render('admin.account.activities', compact('activities'));
	}

	/**
	 * delete
	 *
	 * @return void
	 */
	public function delete(): void
	{
        foreach (explode(',', $this->request->items) as $id) {
            ActivitiesModel::deleteWhere('id', $id);
        }
        
        Alert::toast(__('activities_deleted'))->success();
	}

	/**
	 * export data
	 *
	 * @return void
	 */
    public function export(): void
	{
        $activities = ActivitiesModel::select()
            ->subQuery(function ($query) {
                if ($this->request->has('date_start') && $this->request->has('date_end')) {
                    $query->whereBetween('created_at', $this->request->date_start, $this->request->date_end);
                }
            })
            ->orderDesc('created_at')
            ->all();
        
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
