<?php

namespace App\Controllers\Admin;

use App\Database\Models\Medias;
use Framework\Support\Metrics;
use Framework\Routing\Controller;
use App\Database\Models\Messages;
use App\Database\Models\Notifications;

class DashboardController extends Controller
{
	/**
	 * index
	 *
	 * @return void
	 */
	public function index(): void
	{
		$this->render('admin.index', [
            'total_users' => $this->model('users')->count()->single()->value, 
            'inactive_users' => $this->model('users')->count()->where('active', 0)->single()->value,
            'active_users' => $this->model('users')->count()->where('active', 1)->single()->value, 
            'users_metrics' => $this->model('users')->metrics('id', Metrics::COUNT, Metrics::MONTHS), 
            'notifications' => Notifications::findMessages(), 
            'messages' => Messages::findReceivedMessages(),
            'total_medias' => Medias::paginate()->getTotalItems()
        ]);
    }
}
