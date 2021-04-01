<?php

namespace App\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\ReportHelper;
use Framework\Routing\Controller;
use App\Database\Models\MessagesModel;

class MessagesController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $messages = MessagesModel::findMessages();

        $messages_unread = $this->model('messages')
            ->count()
            ->where('recipient', Auth::get()->id)
            ->and('recipient_status', 'unread')
            ->single()
            ->value;

		$this->render('admin.account.messages', compact('messages', 'messages_unread'));
	}

	/**
	 * create
	 *
	 * @return void
	 */
    public function create(): void
	{
        $id = $this->model('messages')->insert([
            'sender' => Auth::get()->id,    
            'recipient' => $this->request->recipient,
            'message' => $this->request->message
        ]);

        $this->model('messages')->updateIfExists($id, ['sender_status' => 'read']);
        $this->log(__('message_sent'));
        $this->back()->withToast(__('message_sent'))->success();
	}
	
	/**
	 * reply
	 *
	 * @return void
	 */
    public function reply(): void
	{
        $id = $this->model('messages')->insert([
            'sender' => Auth::get()->id,
            'recipient' => $this->request->recipient,
            'message' => $this->request->message
        ]);

        $this->model('messages')->updateIfExists($id, ['sender_status' => 'read']);
        $this->log(__('message_sent'));
        $this->back()->withToast(__('message_sent'))->success();
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
            $this->model('messages')->updateIfExists($id, ['recipient_status' => 'read']);
            $this->log(__('message_updated'));
            $this->back()->withToast(__('message_updated'))->success();
		} else {
            $this->model('notifications')->updateBy(['id', 'in', $this->request->items], ['recipient_status' => 'read']);
            $this->log(__('messages_updated'));
			$this->alert('toast', __('messages_updated'))->success();
            $this->response([absolute_url('admin/account/messages')], true);
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
            if ($this->model('messages')->findSingle($id)->sender === Auth::get()->id) {
                $data = 'sender_deleted';
            } elseif ($this->model('messages')->findSingle($id)->recipient === Auth::get()->id) {
                $data = 'recipient_deleted';
            }

            $this->model('messages')->updateIfExists($id, [$data => 1]);
            $this->log(__('message_deleted'));
            $this->back()->withToast(__('message_deleted'))->success();
		} else {
			foreach (explode(',', $this->request->items) as $id) {
				if ($this->model('messages')->findSingle($id)->sender === Auth::get()->id) {
                    $data = 'sender_deleted';
                } elseif ($this->model('messages')->findSingle($id)->recipient === Auth::get()->id) {
                    $data = 'recipient_deleted';
                }
    
                $this->model('messages')->updateIfExists($id, [$data => 1]);
			}
            
            $this->log(__('messages_deleted'));
			$this->alert('toast', __('messages_deleted'))->success();
            $this->response([absolute_url('admin/account/messages')], true);
		}
	}

	/**
	 * export
	 *
	 * @return void
	 */
    public function export(): void
	{
        $messages = $this->model('messages')->select()
            ->subQuery(function ($query) {
                if ($this->request->has('date_start') && $this->request->has('date_end')) {
                    $query->whereBetween('created_at', $this->request->date_start, $this->request->date_end);
                }
            })
            ->oldest()
            ->all();
        
        $filename = 'messages_' . date('Y_m_d') . '.' . $this->request->file_type;

        $this->log(__('data_exported'));

		ReportHelper::export($filename, $messages, [
			'sender' => __('sender'), 
			'recipient' => __('recipient'), 
			'message' => __('message'), 
			'created_at' => __('created_at')
		]);
	}
}
