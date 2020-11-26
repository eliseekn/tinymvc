<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
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
		$this->render('admin/activities', [
            'activities' => ActivitiesModel::select()->orderDesc('created_at')->paginate(20)
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
			if (!ActivitiesModel::find('id', $id)->exists()) {
				$this->redirectBack()->withToast(__('activity_not_found'))->error();
			}
	
			ActivitiesModel::delete()->where('id', $id)->persist();
            $this->redirectBack()->withToast(__('activity_deleted'))->success();
		} else {
			$activities_id = explode(',', $this->request->items);

			foreach ($activities_id as $id) {
				ActivitiesModel::delete()->where('id', $id)->persist();
			}
			
			$this->toast(__('activities_deleted'))->success();
		}
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
			$activities = ActivitiesModel::select()
                ->between('created_at', Carbon::parse($date_start)->toDateTimeString(), Carbon::parse($date_end)->toDateTimeString())
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
