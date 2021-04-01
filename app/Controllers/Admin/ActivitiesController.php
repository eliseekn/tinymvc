<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use App\Database\Models\RolesModel;

class ActivitiesController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $activities = $this->model('activities')
            ->select(['id', 'user', 'url', 'ip_address', 'action', 'created_at'])
            ->subQuery(function ($query) {
                if (Auth::get()->role !== RolesModel::ROLE[0]) {
                    $query->where('user', Auth::get()->email);
                }
            })
            ->oldest()
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
        $this->model('activities')->deleteBy('id', 'in', explode(',', $this->request->items));
        $this->alert('toast', __('activities_deleted'))->success();
        $this->response([absolute_url('admin/account/activities')], true);
	}

	/**
	 * export
	 *
	 * @return void
	 */
    public function export(): void
	{
        $activities = $this->model('activities')
            ->select()
            ->subQuery(function ($query) {
                if ($this->request->has('date_start') && $this->request->has('date_end')) {
                    $query->whereBetween('created_at', $this->request->date_start, $this->request->date_end);
                }
            })
            ->oldest()
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
