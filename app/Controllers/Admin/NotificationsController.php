<?php

namespace App\Controllers\Admin;

use App\Helpers\ActivityHelper;
use Framework\Routing\Controller;
use App\Database\Models\NotificationsModel;
use App\Helpers\AuthHelper;
use App\Helpers\NotificationHelper;

class NotificationsController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
    {
        $notifications = NotificationsModel::find('user_id', AuthHelper::user()->id)->orderDesc('created_at')->paginate(20);

        $notifications_unread = NotificationsModel::count()
            ->where('status', 'unread')
            ->andWhere('user_id', AuthHelper::user()->id)
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
        $this->redirectBack()->withToast(__('notifications_created'))->success();
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
			if (!NotificationsModel::find('id', $id)->exists()) {
				$this->redirectBack()->withToast(__('notification_not_found'))->error();
			}
	
            NotificationsModel::update(['status' => 'read'])->where('id', $id)->persist();
            ActivityHelper::log('Notification marked as read');
            $this->redirectBack()->withToast(__('notification_updated'))->success();
		} else {
			$notifications_id = explode(',', $this->request->items);

			foreach ($notifications_id as $id) {
				NotificationsModel::update(['status' => 'read'])->where('id', $id)->persist();
			}
			
            ActivityHelper::log('Notifications marked as read');
			$this->toast(__('notifications_updated'))->success();
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
			if (!NotificationsModel::find('id', $id)->exists()) {
				$this->redirectBack()->withToast(__('notification_not_found'))->error();
			}
	
			NotificationsModel::delete()->where('id', $id)->persist();
            ActivityHelper::log('Notification deleted');
			$this->redirectBack()->withToast(__('notification_deleted'))->success();
		} else {
            $notifications_id = explode(',', $this->request->items);

			foreach ($notifications_id as $id) {
				NotificationsModel::delete()->where('id', $id)->persist();
			}
			
            ActivityHelper::log('Notifications deleted');
			$this->toast(__('notifications_deleted'))->success();
		}
	}
}
