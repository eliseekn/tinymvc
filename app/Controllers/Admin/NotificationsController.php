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
        $notifications = NotificationsModel::findBy('user_id', Auth::get()->id)
            ->orderDesc('created_at')
            ->paginate(20);

        $notifications_unread = NotificationsModel::count()
            ->where('status', 'unread')
            ->and('user_id', Auth::get()->id)
            ->single()
            ->value;

        $this->render('admin.account.notifications', compact('notifications', 'notifications_unread'));
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
            NotificationsModel::updateIfExists($id, ['status' => 'read']);

            Activity::log(__('notification_updated'));
            $this->redirect()->withToast(__('notification_updated'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				NotificationsModel::updateIfExists($id, ['status' => 'read']);
			}
			
            Activity::log(__('notifications_updated'));
			Alert::toast(__('notifications_updated'))->success();

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
			NotificationsModel::deleteIfExists($id);

            Activity::log(__('notification_deleted'));
            $this->redirect()->withToast(__('notification_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				NotificationsModel::deleteIfExists($id);
			}
			
            Activity::log(__('notifications_deleted'));
			Alert::toast(__('notifications_deleted'))->success();

			$this->response(['redirect' => absolute_url('admin/account/notifications')], true);
		}
	}
}
