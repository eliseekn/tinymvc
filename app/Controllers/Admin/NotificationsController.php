<?php

namespace App\Controllers\Admin;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\Support\Alert;
use App\Requests\NotificationRequest;
use App\Database\Models\NotificationsModel;

class NotificationsController
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
    {
        View::render('admin/notifications', [
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
        
        if (is_array($validate)) {
            Redirect::back()->withError($validate);
        }

	    NotificationsModel::insert(['text' => Request::getField('text')]);
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
				Alert::toast(__('notification_not_found'))->error();
			}
	
			NotificationsModel::update(['status' => 'read'])->where('id', $id)->persist();
            Alert::toast(__('notification_updated'))->success();
            Redirect::back()->only();
		} else {
			$notifications_id = json_decode(Request::getRawData(), true);
			$notifications_id = $notifications_id['items'];

			foreach ($notifications_id as $id) {
				NotificationsModel::update(['status' => 'read'])->where('id', $id)->persist();
			}
			
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
			if (!NotificationsModel::find('id', $id)->exists()) {
				Alert::toast(__('notification_not_found'))->error();
			}
	
			NotificationsModel::delete()->where('id', $id)->persist();
			Alert::toast(__('notification_deleted'))->success();
            Redirect::back()->only();
		} else {
			$notifications_id = json_decode(Request::getRawData(), true);
			$notifications_id = $notifications_id['items'];

			foreach ($notifications_id as $id) {
				NotificationsModel::delete()->where('id', $id)->persist();
			}
			
			Alert::toast(__('notification_deleted'))->success();
		}
	}
}
