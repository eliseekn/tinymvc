<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use Framework\HTTP\Request;
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
            'activities' => ActivitiesModel::select()->orderDesc('created_at')->paginate(50)
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
				$this->toast(__('activity_not_found'))->error();
			}
	
			ActivitiesModel::delete()->where('id', $id)->persist();
			$this->toast(__('activity_deleted'))->success();
            $this->redirect()->only();
		} else {
			$activities_id = json_decode(Request::getRawData(), true);
			$activities_id = $activities_id['items'];

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
        $date_start = Request::getField('date_start');
        $date_end = Request::getField('date_end');

		if (!empty($date_start) && !empty($date_end)) {
			$users = ActivitiesModel::select()
                ->between('created_at', Carbon::parse($date_start)->toDateTimeString(), Carbon::parse($date_end)->toDateTimeString())
                ->orderAsc('name')
                ->all();
		} else {
			$users = ActivitiesModel::select()->orderAsc('name')->all();
        }
        
        $filename = 'activities_' . date('Y_m_d') . '.' . Request::getField('file_type');

		ReportHelper::export($filename, $users, [
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
