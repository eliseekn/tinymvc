<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Helpers\AuthHelper;
use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use App\Database\Models\MessagesModel;
use App\Database\Models\UsersModel;
use App\Helpers\ActivityHelper;

class MessagesController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
	{
        $messages = MessagesModel::get()->paginate(20);

        $messages_unread = MessagesModel::count()
            ->where('recipient', AuthHelper::user()->id)
            ->andWhere('status', 'unread')
            ->single()
            ->value;

		$this->render('admin/messages', compact('messages', 'messages_unread'));
	}

	/**
	 * send new message
	 *
	 * @return void
	 */
    public function create(): void
	{
        MessagesModel::insert([
            'sender' => AuthHelper::user()->id,
            'recipient' => $this->request->recipient,
            'message' => $this->request->message
        ]);

        ActivityHelper::log('Message sent to ' . UsersModel::find('id', $this->request->recipient)->single()->email);
        $this->redirectBack()->withToast(__('message_sent'))->success();
	}
	
	/**
	 * reply to message
	 *
	 * @return void
	 */
    public function reply(): void
	{
        $id = MessagesModel::insert([
            'sender' => AuthHelper::user()->id,
            'recipient' => $this->request->recipient,
            'message' => $this->request->message
        ]);

        MessagesModel::update(['status' => 'read'])->where('id', $id)->persist();
        ActivityHelper::log('Message replied to ' . UsersModel::find('id', $this->request->recipient)->single()->email);
        $this->redirectBack()->withToast(__('message_sent'))->success();
	}
	
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
        if (!MessagesModel::find('id', $id)->exists()) {
            $this->redirectBack()->withToast(__('message_not_found'))->error();
        }

        MessagesModel::update(['status' => 'read'])->where('id', $id)->persist();
        ActivityHelper::log('Message marked as read');
        $this->redirectBack()->withToast(__('message_updated'))->success();
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
			if (!MessagesModel::find('id', $id)->exists()) {
				$this->redirectBack()->withToast(__('message_not_found'))->error();
			}
	
            MessagesModel::delete()->where('id', $id)->persist();
            ActivityHelper::log('Message deleted');
            $this->redirectBack()->withToast(__('message_deleted'))->success();
		} else {
            $messages_id = explode(',', $this->request->items);

			foreach ($messages_id as $id) {
				MessagesModel::delete()->where('id', $id)->persist();
			}
            
            ActivityHelper::log('Messages deleted');
			$this->toast(__('messages_deleted'))->success();
		}
	}

	/**
	 * export data
	 *
	 * @return void
	 */
    public function export(): void
	{
        $date_start = $this->request->has('date_start') ? $this->request->date_start : null;
        $date_end = $this->request->has('date_end') ? $this->request->date_end : null;

		if (!is_null($date_start) && !is_null($date_end)) {
			$messages = MessagesModel::select()
                ->between('created_at', Carbon::parse($date_start)->toDateTimeString(), Carbon::parse($date_end)->toDateTimeString())
                ->orderDesc('created_at')
                ->all();
		} else {
			$messages = MessagesModel::select()->orderDesc('created_at')->all();
        }
        
        $filename = 'messages_' . date('Y_m_d') . '.' . $this->request->file_type;

        ActivityHelper::log('Messages exported');

		ReportHelper::export($filename, $messages, [
			'sender' => __('sender'), 
			'recipient' => __('recipient'), 
			'message' => __('message'), 
			'created_at' => __('created_at')
		]);
	}
}
