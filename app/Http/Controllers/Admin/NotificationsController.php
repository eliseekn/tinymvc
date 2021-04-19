<?php

namespace App\Http\Controllers\Admin;

use Framework\Http\Request;
use Framework\Routing\Controller;
use App\Helpers\NotificationHelper;
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
	 * create
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function create(Request $request): void
	{
        NotificationHelper::create($request->message);
        redirect()->back()->withToast('success', __('notifications_created'))->go();
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
            $this->log(__('notification_updated'));
            redirect()->back()->withToast('success', __('notification_updated'))->go();
		} else {
            $this->notifications->updateBy(['id', 'in', $request->items], ['status' => 1]);
            $this->log(__('notifications_updated'));
			$this->toast('success', __('notifications_updated'));
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
			$this->notifications->deleteIfExists($id);
            $this->log(__('notification_deleted'));
            redirect()->back()->withToast('success', __('notification_deleted'))->go();
		} else {
            $this->notifications->deleteBy('id', 'in', explode(',', $request->items));
            $this->log(__('notifications_deleted'));
			$this->toast('success', __('notifications_deleted'));
			response()->json(['redirect' => route('notifications.index')]);
		}
	}
}
