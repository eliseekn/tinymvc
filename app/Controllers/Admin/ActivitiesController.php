<?php

namespace App\Controllers\Admin;

use App\Helpers\Report;
use Framework\Http\Request;
use Framework\Routing\Controller;
use App\Database\Repositories\Activities;

class ActivitiesController extends Controller
{
    /**
     * @var \App\Database\Repositories\Activities $activities
     */
    private $activities;
    
    /**
     * __construct
     *
     * @param  \App\Database\Repositories\Activities $activities
     * @return void
     */
    public function __construct(Activities $activities)
    {
        $this->activities = $activities;
    }

    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $data = $this->activities->findAllPaginate();
		$this->render('admin.account.activities', compact('data'));
	}

	/**
	 * delete
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function delete(Request $request): void
	{
        if ($this->activities->deleteById($request)) {
            $this->toast('error', __('activity_not_deleted'));
        }

        $this->toast('success', __('activities_deleted'));
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
        $data = $this->activities->findAllDateRange($request->date_start, $request->date_end);
        $filename = 'activities_' . date('Y_m_d_His') . '.' . $request->file_type;

        $this->log(__('data_exported'));

		Report::generate($filename, $data, [
			'user' => __('user'), 
			'url' => __('url'), 
			'method' => __('method'), 
			'ip_address' => __('ip_address'), 
			'action' => __('action'), 
			'created_at' => __('created_at')
		]);
	}
}
