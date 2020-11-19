<?php

namespace App\Controllers\Admin;

use Framework\Routing\Controller;
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
        $this->render('admin/notifications', [
            'notifications' => NotificationsModel::select()->orderDesc('created_at')->paginate(20),
            'notifications_unread' => NotificationsModel::count()->where('status', 'unread')->single()->value,
        ]);
    }

	/**
	 * create
	 *
	 * @return void
	 */
	public function create(): void
	{
        NotificationsModel::insert(['message' => $this->request->message]);
        $this->toast(__('notifications_created'))->success();
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
				$this->toast(__('notification_not_found'))->error();
			}
	
			NotificationsModel::update(['status' => 'read'])->where('id', $id)->persist();
            $this->toast(__('notification_updated'))->success();
		} else {
			$notifications_id = explode(',', $this->request->items);

			foreach ($notifications_id as $id) {
				NotificationsModel::update(['status' => 'read'])->where('id', $id)->persist();
			}
			
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
				$this->toast(__('notification_not_found'))->error();
			}
	
			NotificationsModel::delete()->where('id', $id)->persist();
			$this->toast(__('notification_deleted'))->success();
		} else {
            $notifications_id = explode(',', $this->request->items);

			foreach ($notifications_id as $id) {
				NotificationsModel::delete()->where('id', $id)->persist();
			}
			
			$this->toast(__('notifications_deleted'))->success();
		}
	}
}
