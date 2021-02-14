<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\Activity;
use Framework\Support\Alert;
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
        $messages = MessagesModel::messages()->paginate(20);

        $messages_unread = MessagesModel::count()
            ->where('recipient', Auth::get()->id)
            ->and('recipient_status', 'unread')
            ->single()
            ->value;

		$this->render('admin.account.messages', compact('messages', 'messages_unread'));
	}

	/**
	 * send new message
	 *
	 * @return void
	 */
    public function create(): void
	{
        $id = MessagesModel::insert([
            'sender' => Auth::get()->id,    
            'recipient' => $this->request->recipient,
            'message' => $this->request->message
        ]);

        MessagesModel::update(['sender_status' => 'read'])->where('id', $id)->persist();
        Activity::log(__('message_sent'));
        $this->redirect()->withToast(__('message_sent'))->success();
	}
	
	/**
	 * reply to message
	 *
	 * @return void
	 */
    public function reply(): void
	{
        $id = MessagesModel::insert([
            'sender' => Auth::get()->id,
            'recipient' => $this->request->recipient,
            'message' => $this->request->message
        ]);

        MessagesModel::update(['sender_status' => 'read'])->where('id', $id)->persist();
        Activity::log(__('message_sent'));
        $this->redirect()->withToast(__('message_sent'))->success();
	}
	
	/**
	 * update
	 *
     * @param  int $id
	 * @return void
	 */
	public function update(int $id): void
	{
        if (!MessagesModel::find($id)->exists()) {
            $this->redirect()->withToast(__('message_not_found'))->error();
        }

        MessagesModel::update(['recipient_status' => 'read'])->where('id', $id)->persist();
        Activity::log(__('message_updated'));
        $this->redirect()->withToast(__('message_updated'))->success();
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
			if (!MessagesModel::find($id)->exists()) {
				$this->redirect()->withToast(__('message_not_found'))->error();
			}
	
            MessagesModel::deleteWhere('id', $id);
            Activity::log(__('message_deleted'));
            $this->redirect()->withToast(__('message_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				MessagesModel::deleteWhere('id', $id);
			}
            
            Activity::log(__('messages_deleted'));
			Alert::toast(__('messages_deleted'))->success();
		}
	}

	/**
	 * export data
	 *
	 * @return void
	 */
    public function export(): void
	{
        $messages = MessagesModel::select()
            ->subQuery(function ($query) {
                if ($this->request->has('date_start') && $this->request->has('date_end')) {
                    $query->whereBetween('created_at', $this->request->date_start, $this->request->date_end);
                }
            })
            ->orderDesc('created_at')
            ->all();
        
        $filename = 'messages_' . date('Y_m_d') . '.' . $this->request->file_type;

        Activity::log(__('data_exported'));

		ReportHelper::export($filename, $messages, [
			'sender' => __('sender'), 
			'recipient' => __('recipient'), 
			'message' => __('message'), 
			'created_at' => __('created_at')
		]);
	}
}
