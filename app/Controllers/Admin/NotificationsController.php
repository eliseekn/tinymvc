<?php

namespace App\Controllers\Admin;

use Framework\Routing\Controller;
use App\Helpers\NotificationHelper;
use App\Database\Models\Notifications;

class NotificationsController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        $notifications = Notifications::paginate();
        $notifications_unread = Notifications::unreadCount();
        $this->render('admin.account.notifications', compact('notifications', 'notifications_unread'));
    }

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
        NotificationHelper::create($this->request('message'));
        $this->back()->withToast(__('notifications_created'))->success();
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
            $this->model('notifications')->updateIfExists($id, ['status' => 'read']);
            $this->log(__('notification_updated'));
            $this->back()->withToast(__('notification_updated'))->success();
		} else {
            $this->model('notifications')->updateBy(['id', 'in', $this->request('items')], ['status' => 'read']);
            $this->log(__('notifications_updated'));
			$this->alert('toast', __('notifications_updated'))->success();
            $this->response(['redirect' => absolute_url('admin/account/notifications')], true);
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
			$this->model('notifications')->deleteIfExists($id);
            $this->log(__('notification_deleted'));
            $this->back()->withToast(__('notification_deleted'))->success();
		} else {
            $this->model('notifications')->deleteBy('id', 'in', explode(',', $this->request('items')));
            $this->log(__('notifications_deleted'));
			$this->alert('toast', __('notifications_deleted'))->success();
			$this->response(['redirect' => absolute_url('admin/account/notifications')], true);
		}
	}
}
