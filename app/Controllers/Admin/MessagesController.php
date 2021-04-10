<?php

namespace App\Controllers\Admin;

use App\Helpers\Report;
use Framework\Http\Request;
use App\Database\Models\Messages;
use Framework\Routing\Controller;

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
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
    public function create(Request $request): void
	{
        $id = Messages::store($request);

        $this->model('messages')->updateIfExists($id, ['sender_status' => 'read']);
        $this->log(__('message_sent'));
        redirect()->back()->withToast(__('message_sent'))->success();
	}
	
	/**
	 * reply
	 *
     * @param  \Framework\Http\Request $request
     * @param  int $id
	 * @return void
	 */
    public function reply(Request $request, int $id): void
	{
        $_id = Messages::store($request);

        $this->model('messages')->updateIfExists($id, ['recipient_status' => 'read']);
        $this->model('messages')->updateIfExists($_id, ['sender_status' => 'read']);
        $this->log(__('message_sent'));
        redirect()->back()->withToast(__('message_sent'))->success();
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
        Messages::updateReadStatus($request, $id);

        if (!is_null($id)) {
            $this->log(__('message_updated'));
            redirect()->back()->withToast(__('message_updated'))->success();
		} else {
            $this->log(__('messages_updated'));
			$this->alert('toast', __('messages_updated'))->success();
            response()->json(['redirect' => route('messages.index')]);
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
        Messages::updateDeletedStatus($request, $id);

        if (!is_null($id)) {
            $this->log(__('message_deleted'));
            redirect()->back()->withToast(__('message_deleted'))->success();
		} else {
            $this->log(__('messages_deleted'));
			$this->alert('toast', __('messages_deleted'))->success();
            response()->json(['redirect' => route('messages.index')]);
		}
	}

	/**
	 * export
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
    public function export(Request $request): void
	{
        $messages = Messages::fromDateRange($request->date_start, $request->date_end);
        $filename = 'messages_' . date('Y_m_d_His') . '.' . $request->file_type;

        $this->log(__('data_exported'));

		Report::generate($filename, $messages, [
			'sender' => __('sender'), 
			'recipient' => __('recipient'), 
			'message' => __('message'), 
			'created_at' => __('created_at')
		]);
	}
}
