<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Activity;
use Framework\Http\Request;
use Framework\Support\Alert;
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
        $messages_deleted = $this->messages->deletedCount();
		$this->render('admin.account.messages', compact('data', 'messages_unread', 'messages_deleted'));
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
        Activity::log(__('message_sent'));
        $this->redirect()->back()->withToast('success', __('message_sent'))->go();
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
        Activity::log(__('message_sent'));
        $this->redirect()->back()->withToast('success', __('message_sent'))->go();
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
            Activity::log(__('message_updated'));
            $this->redirect()->back()->withToast('success', __('message_updated'))->go();
		} else {
            Activity::log(__('messages_updated'));
			Alert::toast(__('messages_updated'))->success();
            $this->response()->json(['redirect' => route('messages.index')]);
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
            Activity::log(__('message_deleted'));
            $this->redirect()->back()->withToast('success', __('message_deleted'))->go();
		} else {
            Activity::log(__('messages_deleted'));
			Alert::toast(__('messages_deleted'))->success();
            $this->response()->json(['redirect' => route('messages.index')]);
		}
	}
}
