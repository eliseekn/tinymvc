<?php

namespace App\Controllers\Admin;

use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use App\Database\Models\Activities;

class ActivitiesController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $activities = Activities::paginate();
		$this->render('admin.account.activities', compact('activities'));
	}

	/**
	 * delete
	 *
	 * @return void
	 */
	public function delete(): void
	{
        if (Activities::deleteById($this->request())) {
            $this->alert('toast', __('activity_not_deleted'))->error();
        }

        $this->alert('toast', __('activities_deleted'))->success();
        $this->response(['redirect' => absolute_url('admin/account/activities')], true);
	}

	/**
	 * export
	 *
	 * @return void
	 */
    public function export(): void
	{
        $activities = $this->model('activities')
            ->between($this->request('date_start'), $this->request('date_end'))
            ->oldest()
            ->all();

        $filename = 'activities_' . date('Y_m_d_His') . '.' . $this->request('file_type');

        $this->log(__('data_exported'));

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
