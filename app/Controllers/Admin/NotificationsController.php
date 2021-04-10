<?php

namespace App\Controllers\Admin;

use Framework\Http\Request;
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
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function create(Request $request): void
	{
        NotificationHelper::create($request->message);
        redirect()->back()->withToast(__('notifications_created'))->success();
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
            $this->model('notifications')->updateIfExists($id, ['status' => 'read']);
            $this->log(__('notification_updated'));
            redirect()->back()->withToast(__('notification_updated'))->success();
		} else {
            $this->model('notifications')->updateBy(['id', 'in', $request->items], ['status' => 'read']);
            $this->log(__('notifications_updated'));
			$this->alert('toast', __('notifications_updated'))->success();
            response()->json(['redirect' => route('notifications.index')]);
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
			$this->model('notifications')->deleteIfExists($id);
            $this->log(__('notification_deleted'));
            redirect()->back()->withToast(__('notification_deleted'))->success();
		} else {
            $this->model('notifications')->deleteBy('id', 'in', explode(',', $request->items));
            $this->log(__('notifications_deleted'));
			$this->alert('toast', __('notifications_deleted'))->success();
			response()->json(['redirect' => route('notifications.index')]);
		}
	}
}
