<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\Controller;
use App\Requests\NotificationRequest;
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
            'notifications' => NotificationsModel::select()->orderDesc('created_at')->paginate(50),
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
        $validate = NotificationRequest::validate(Request::getFields());
        
        if ($validate->fails()) {
            $this->redirect()->withError($validate::$errors);
        }

        NotificationsModel::insert(['message' => Request::getField('message')]);
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
            $this->redirect()->only();
		} else {
			$notifications_id = json_decode(Request::getRawData(), true);
			$notifications_id = $notifications_id['items'];

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
            $this->redirect()->only();
		} else {
			$notifications_id = json_decode(Request::getRawData(), true);
			$notifications_id = $notifications_id['items'];

			foreach ($notifications_id as $id) {
				NotificationsModel::delete()->where('id', $id)->persist();
			}
			
			$this->toast(__('notification_deleted'))->success();
		}
	}
}
