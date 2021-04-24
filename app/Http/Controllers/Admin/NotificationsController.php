<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Activity;
use Framework\Http\Request;
use Framework\Support\Alert;
use Framework\Routing\Controller;
use App\Database\Repositories\Notifications;

class NotificationsController extends Controller
{
    /**
     * @var \App\Database\Repositories\Notifications $notifications
     */
    private $notifications;
    
    /**
     * __construct
     *
     * @param  \App\Database\Repositories\Notifications $notifications
     * @return void
     */
    public function __construct(Notifications $notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        $data = $this->notifications->findAllPaginate();
        $notifications_unread = $this->notifications->unreadCount();
        $this->render('admin.account.notifications', compact('data', 'notifications_unread'));
    }

	/**
	 * update
	 *
     * @param  \Framework\Http\Request $request
     * @param  int|null $id
	 * @return void
	 */
	public function update(Request $request, ?int $id = null): void
	{
        if (!is_null($id)) {
            $this->notifications->updateIfExists($id, ['status' => 1]);
            Activity::log(__('notification_updated'));
            $this->redirect()->back()->withToast('success', __('notification_updated'))->go();
		} else {
            $this->notifications->updateBy(['id', 'in', $request->items], ['status' => 1]);
            Activity::log(__('notifications_updated'));
			Alert::toast(__('notifications_updated'))->success();
            $this->response()->json(['redirect' => route('notifications.index')]);
		}
    }
	
	/**
	 * delete
	 *
     * @param  \Framework\Http\Request $request
     * @param  int|null $id
	 * @return void
	 */
	public function delete(Request $request, ?int $id = null): void
	{
		if (!is_null($id)) {
			$this->notifications->deleteIfExists($id);
            Activity::log(__('notification_deleted'));
            $this->redirect()->back()->withToast('success', __('notification_deleted'))->go();
		} else {
            $this->notifications->deleteBy('id', 'in', explode(',', $request->items));
            Activity::log(__('notifications_deleted'));
			Alert::toast(__('notifications_deleted'))->success();
			$this->response()->json(['redirect' => route('notifications.index')]);
		}
	}
}
