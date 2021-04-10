<?php

namespace App\Controllers\Admin;

use Framework\Http\Request;
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
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function delete(Request $request): void
	{
        if (Activities::deleteById($request)) {
            $this->alert('toast', __('activity_not_deleted'))->error();
        }

        $this->alert('toast', __('activities_deleted'))->success();
        response()->json(['redirect' => route('activities.index')]);
	}

	/**
	 * export
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
    public function export(Request $request): void
	{
        $activities = Activities::fromDateRange($request->date_start, $request->date_end);
        $filename = 'activities_' . date('Y_m_d_His') . '.' . $request->file_type;

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
