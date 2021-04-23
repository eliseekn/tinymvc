<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Report;
use Framework\Http\Request;
use Framework\Http\Validator;
use Framework\Routing\Controller;
use App\Database\Repositories\Tickets;
use App\Database\Repositories\TicketMessages;
use App\Helpers\NotificationHelper;

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

        NotificationHelper::create(__('ticket_open'), route('tickets.read', $id));
		redirect()->route('tickets.read', $id)->go();
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
        Validator::validate($request->only('message'), ['message' => 'required|max_len,255'])->redirectOnFail();
        $messages->store($request);

        NotificationHelper::create(__('new_message'), route('tickets.read', $request->ticket_id));
		redirect()->route('tickets.read', $request->ticket_id)->go();
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
        $this->tickets->refresh($id, $status);

        $message = $status === 1 ? __('ticket_open') : __('ticket_closed');
        NotificationHelper::create($message, route('tickets.read', $id));

		redirect()->route('tickets.read', $id)->withToast('success', $message)->go();
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
        if (!is_null($id)) {
            if (!$this->tickets->deleteIfExists($id)) {
                redirect()->back()->go();
			}

            redirect()->route('tickets.index')->go();
		} else {
            $this->tickets->deleteBy('id', 'in', explode(',', $request->items));
            response()->json(['redirect' => route('tickets.index')]);
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
        $tickets = $this->tickets
            ->select()
            ->subQuery(function($query) use ($request) {
                if (!$request->filled('date_start') && !$request->filled('date_end')) {
                    $query->whereBetween('created_at', $request->date_start, $request->date_end);
                }
            })
            ->oldest()
            ->all();

        $filename = 'tickets_' . date('Y_m_d_His') . '.' . $request->file_type;

		Report::generate($filename, $tickets, [
			//
		]);
	}
}
