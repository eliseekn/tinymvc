<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Report;
use Framework\Http\Request;
use Framework\Routing\Controller;
use App\Database\Repositories\Messages;

class MessagesController extends Controller
{
    /**
     * @var \App\Database\Repositories\Messages $messages
     */
    private $messages;
    
    /**
     * __construct
     *
     * @param  \App\Database\Repositories\Messages $messages
     * @return void
     */
    public function __construct(Messages $messages)
    {
        $this->messages = $messages;
    }

    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $data = $this->messages->findAllPaginate();
        $messages_unread = $this->messages->unreadCount();
		$this->render('admin.account.messages', compact('data', 'messages_unread'));
	}

	/**
	 * create
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
    public function create(Request $request): void
	{
        $id = $this->messages->store($request);

        $this->messages->updateIfExists($id, ['sender_read' => 1]);
        $this->log(__('message_sent'));
        redirect()->back()->withToast('success', __('message_sent'))->go();
	}
	
	/**
	 * reply
	 *
     * @param  \Framework\Http\Request $request
     * @param  int $message_id
	 * @return void
	 */
    public function reply(Request $request, int $message_id): void
	{
        $id = $this->messages->store($request);

        $this->messages->updateIfExists($message_id, ['recipient_read' => 1]);
        $this->messages->updateIfExists($id, ['sender_read' => 1]);
        $this->log(__('message_sent'));
        redirect()->back()->withToast('success', __('message_sent'))->go();
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
        $this->messages->updateReadStatus($request, $id);

        if (!is_null($id)) {
            $this->log(__('message_updated'));
            redirect()->back()->withToast('success', __('message_updated'))->go();
		} else {
            $this->log(__('messages_updated'));
			$this->toast('success', __('messages_updated'));
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
        $this->messages->updateDeletedStatus($request, $id);

        if (!is_null($id)) {
            $this->log(__('message_deleted'));
            redirect()->back()->withToast('success', __('message_deleted'))->go();
		} else {
            $this->log(__('messages_deleted'));
			$this->toast('success', __('messages_deleted'));
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
        $data = $this->messages->findAllDateRange($request->date_start, $request->date_end);
        $filename = 'messages_' . date('Y_m_d_His') . '.' . $request->file_type;

        $this->log(__('data_exported'));

		Report::generate($filename, $data, [
			'sender' => __('sender'), 
			'recipient' => __('recipient'), 
			'message' => __('message'), 
			'created_at' => __('created_at')
		]);
	}
}
