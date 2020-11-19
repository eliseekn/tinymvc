<?php

namespace App\Controllers\Admin;

use Carbon\Carbon;
use App\Helpers\AuthHelper;
use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use App\Database\Models\MessagesModel;

class MessagesController extends Controller
{
    /**
     * display list
     *
     * @return void
     */
    public function index(): void
	{
        $messages = MessagesModel::select(['messages.*', 'u1.email AS sender_email', 'u2.email AS recipient_email'])
            ->join('users AS u1', 'messages.sender', 'u1.id')
            ->join('users AS u2', 'messages.recipient', 'u2.id')
            ->where('messages.recipient', AuthHelper::getSession()->id)
            ->orWhere('messages.sender', AuthHelper::getSession()->id)
            ->orderDesc('messages.created_at')
            ->paginate(20);

        $messages_unread = MessagesModel::count()
            ->where('recipient', AuthHelper::getSession()->id)
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
            'sender' => AuthHelper::getSession()->id,
            'recipient' => $this->request->recipient,
            'message' => $this->request->message
        ]);

        $this->toast(__('message_sent'))->success();
        $this->redirectBack()->only();
	}
	
	/**
	 * reply to message
	 *
	 * @return void
	 */
    public function reply(): void
	{
        $id = MessagesModel::insert([
            'sender' => AuthHelper::getSession()->id,
            'recipient' => $this->request->recipient,
            'message' => $this->request->message
        ]);

        MessagesModel::update(['status' => 'read'])->where('id', $id)->persist();
        
        $this->toast(__('message_sent'))->success();
        $this->redirectBack()->only();
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
            $this->toast(__('message_not_found'))->error();
        }

        MessagesModel::update(['status' => 'read'])->where('id', $id)->persist();
        $this->toast(__('message_updated'))->success();
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
				$this->toast(__('message_not_found'))->error();
			}
	
			MessagesModel::delete()->where('id', $id)->persist();
			$this->toast(__('message_deleted'))->success();
		} else {
            $messages_id = explode(',', $this->request->items);

			foreach ($messages_id as $id) {
				MessagesModel::delete()->where('id', $id)->persist();
			}
			
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

		ReportHelper::export($filename, $messages, [
			'sender' => __('sender'), 
			'recipient' => __('recipient'), 
			'message' => __('message'), 
			'created_at' => __('created_at')
		]);
	}
}
