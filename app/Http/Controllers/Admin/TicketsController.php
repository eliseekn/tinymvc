<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\Activity;
use Framework\Http\Request;
use Framework\Support\Alert;
use Framework\Http\Validator;
use Framework\Routing\Controller;
use App\Helpers\NotificationHelper;
use App\Database\Repositories\Tickets;
use App\Database\Repositories\TicketMessages;

class TicketsController extends Controller
{
    /**
     * @var \App\Database\Repositories\Tickets $tickets
     */
    private $tickets;

    /**
     * __construct
     *
     * @param  \App\Database\Repositories\Tickets $tickets
     * @return void
     */
    public function __construct(Tickets $tickets)
    {
        $this->tickets = $tickets;
    }

    /**
     * index
     *
     * @param  int|null $user_id
     * @return void
     */
    public function index(?int $user_id = null): void
	{
        $data = $this->tickets->findAllByUserPaginate($user_id);
		$this->render('admin.account.support.index', compact('data'));
	}

    /**
	 * new
	 * 
	 * @return void
	 */
    public function new(): void
	{
		$this->render('admin.account.support.new');
	}
	
	/**
	 * read
	 * 
     * @param  \App\Database\Repositories\TicketMessages $messages
	 * @param  int $id
	 * @return void
	 */
	public function read(TicketMessages $messages, int $id): void
	{
        $data = $this->tickets->findSingle($id);
        $messages = $messages->findAllByTicketPaginate($id);
        $this->render('admin.account.support.read', compact('data', 'messages'));
	}

	/**
	 * create
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
    public function create(Request $request): void
	{
        Validator::validate($request->only('object'), ['object' => 'required|max_len,255'])->redirectOnFail();

        $id = $this->tickets->store($request);
        NotificationHelper::create(__('new_ticket_open'), route('tickets.read', $id));

        Activity::log(__('new_ticket_open'));
		$this->redirect()->route('tickets.read', $id)->withToast('success', __('ticket_open'))->go();
	}

	/**
	 * create
	 *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\TicketMessages $messages
	 * @return void
	 */
    public function createMessage(Request $request, TicketMessages $messages): void
	{
        $messages->store($request);
        NotificationHelper::create(__('new_message'), route('tickets.read', $request->ticket_id));

        Activity::log(__('new_message'));
		$this->redirect()->route('tickets.read', $request->ticket_id)->withToast('success', __('message_sent'))->go();
	}
    
	/**
	 * update
	 *
     * @param  int $id
     * @param  int $status
	 * @return void
	 */
	public function update(int $id, int $status): void
	{
        $this->tickets->updateIfExists($id, ['status' => $status]);

        $message = $status === 1 ? __('ticket_open') : __('ticket_closed');
        NotificationHelper::create($message, route('tickets.read', $id));

        Activity::log($message);
		$this->redirect()->route('tickets.read', $id)->withToast('success', $message)->go();
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
        $this->tickets->flush($request, $id);

		if (!is_null($id)) {
            Activity::log(__('ticket_deleted'));
            $this->redirect()->route('tickets.index', Auth::get('id'))->withToast('success', __('ticket_deleted'))->go();
		} else {
            Activity::log(__('tickets_deleted'));
            Alert::toast(__('tickets_deleted'))->success();
            $this->response()->json(['redirect' => route('tickets.index', Auth::get('id'))]);
        }
	}
}
