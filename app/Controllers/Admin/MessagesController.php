<?php

namespace App\Controllers\Admin;

use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use App\Database\Models\Messages;

class MessagesController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $messages = Messages::paginate();
        $messages_unread = Messages::unreadCount();
		$this->render('admin.account.messages', compact('messages', 'messages_unread'));
	}

	/**
	 * create
	 *
	 * @return void
	 */
    public function create(): void
	{
        $id = Messages::store($this->request());

        $this->model('messages')->updateIfExists($id, ['sender_status' => 'read']);
        $this->log(__('message_sent'));
        $this->redirect()->back()->withToast(__('message_sent'))->success();
	}
	
	/**
	 * reply
	 *
     * @param  int $id
	 * @return void
	 */
    public function reply(int $id): void
	{
        $_id = Messages::store($this->request());

        $this->model('messages')->updateIfExists($id, ['recipient_status' => 'read']);
        $this->model('messages')->updateIfExists($_id, ['sender_status' => 'read']);
        $this->log(__('message_sent'));
        $this->redirect()->back()->withToast(__('message_sent'))->success();
	}
	
	/**
	 * update
	 *
     * @param  int|null $id
	 * @return void
	 */
	public function update(?int $id = null): void
	{
        Messages::updateReadStatus($this->request(), $id);

        if (!is_null($id)) {
            $this->log(__('message_updated'));
            $this->redirect()->back()->withToast(__('message_updated'))->success();
		} else {
            $this->log(__('messages_updated'));
			$this->alert('toast', __('messages_updated'))->success();
            $this->response(['redirect' => route('messages.index')], true);
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
        Messages::updateDeletedStatus($this->request(), $id);

        if (!is_null($id)) {
            $this->log(__('message_deleted'));
            $this->redirect()->back()->withToast(__('message_deleted'))->success();
		} else {
            $this->log(__('messages_deleted'));
			$this->alert('toast', __('messages_deleted'))->success();
            $this->response(['redirect' => route('messages.index')], true);
		}
	}

	/**
	 * export
	 *
	 * @return void
	 */
    public function export(): void
	{
        $messages = $this->model('messages')
            ->between($this->request('date_start'), $this->request('date_end'))
            ->oldest()
            ->all();
        
        $filename = 'messages_' . date('Y_m_d_His') . '.' . $this->request('file_type');

        $this->log(__('data_exported'));

		ReportHelper::export($filename, $messages, [
			'sender' => __('sender'), 
			'recipient' => __('recipient'), 
			'message' => __('message'), 
			'created_at' => __('created_at')
		]);
	}
}
