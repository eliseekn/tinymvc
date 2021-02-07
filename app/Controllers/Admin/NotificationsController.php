<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\Activity;
use Framework\Support\Alert;
use Framework\Routing\Controller;
use App\Helpers\NotificationHelper;
use App\Database\Models\NotificationsModel;

class NotificationsController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
    {
        $notifications = NotificationsModel::findBy('user_id', Auth::get()->id)->orderDesc('created_at')->paginate(20);

        $notifications_unread = NotificationsModel::count()
            ->where('status', 'unread')
            ->and('user_id', Auth::get()->id)
            ->single()
            ->value;

        $this->render('admin/notifications', compact('notifications', 'notifications_unread'));
    }

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
        NotificationHelper::create($this->request->message);
        $this->redirect()->withToast(__('notifications_created'))->success();
    }
    
	/**
	 * update
	 *
     * @param  int|null $id
	 * @return void
	 */
	public function update(?int $id = null): void
	{
        if (!is_null($id)) {
			if (!NotificationsModel::find($id)->exists()) {
				$this->redirect()->withToast(__('notification_not_found'))->error();
			}
	
            NotificationsModel::update(['status' => 'read'])->where('id', $id)->persist();
            Activity::log(__('notification_updated'));
            $this->redirect()->withToast(__('notification_updated'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				NotificationsModel::update(['status' => 'read'])->where('id', $id)->persist();
			}
			
            Activity::log(__('notifications_updated'));
			Alert::toast(__('notifications_updated'))->success();
		}
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
			if (!NotificationsModel::find($id)->exists()) {
				$this->redirect()->withToast(__('notification_not_found'))->error();
			}
	
			NotificationsModel::deleteWhere('id', $id);
            Activity::log(__('notification_deleted'));
			$this->redirect()->withToast(__('notification_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				NotificationsModel::deleteWhere('id', $id);
			}
			
            Activity::log(__('notifications_deleted'));
			Alert::toast(__('notifications_deleted'))->success();
		}
	}
}
