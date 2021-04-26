<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Report;
use App\Helpers\Countries;
use Framework\Http\Request;
use Framework\Routing\View;
use Framework\Http\Validator;
use App\Helpers\DownloadHelper;
use Framework\Routing\Controller;
use App\Helpers\NotificationHelper;
use App\Database\Repositories\Users;
use App\Database\Repositories\Invoices;

class InvoicesController extends Controller
{
    /**
     * @var \App\Database\Repositories\Invoices $invoices
     */
    private $invoices;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(Invoices $invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * index
     *
     * @param  int|null $user_id
     * @return void
     */
    public function index(?int $user_id = null): void
	{
        $data = $this->invoices->findAllByUserPaginate($user_id);
        $paid_invoices = $this->invoices->invoicesCount('paid', $user_id);
        $pending_invoices = $this->invoices->invoicesCount('pending', $user_id);
        $expired_invoices = $this->invoices->invoicesCount('expired', $user_id);

		$this->render('admin.invoices.index', compact('data', 'paid_invoices', 'pending_invoices', 'expired_invoices'));
	}

    /**
	 * new
	 * 
     * @param  \App\Database\Repositories\Users $users
	 * @return void
	 */
    public function new(Users $users): void
	{
        $users = $users->findAllByRole('customer');
		$this->render('admin.invoices.new', compact('users'));
	}
	
	/**
	 * edit
	 * 
     * @param  \App\Database\Repositories\Users $users
	 * @param  int $id
	 * @return void
	 */
    public function edit(Users $users, int $id): void
	{
        $data = $this->invoices->findSingleBy($id);
        $users = $users->findAllByRole('customer');
        $this->render('admin.invoices.edit', compact('data', 'users'));
	}
	
	/**
	 * read
	 * 
     * @param  \App\Database\Repositories\Users $users
	 * @param  int $id
	 * @return void
	 */
	public function read(Users $users, int $id): void
	{
        $data = $this->invoices->findSingleByCompany($id);
        $products = json_decode('[' . $data->products . ']');
        $data->products = $products;

        $this->render('admin.invoices.read', compact('data'));
	}

	/**
	 * create
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
    public function create(Request $request): void
	{
        Validator::validate($request->only('products'), ['products' => 'required|max_len,255'])->redirectOnFail();

        $id = $this->invoices->store($request);
        NotificationHelper::create(__('new_invoice'), route('invoices.read', $id));
        
		redirect()->route('invoices.read', $id)->go();
	}
    
	/**
	 * update
	 *
     * @param  \Framework\Http\Request $request
     * @param  int $id
	 * @return void
	 */
	public function update(Request $request, int $id): void
	{
        Validator::validate($request->only('products'), ['products' => 'required|max_len,255'])->redirectOnFail();

        $this->invoices->refresh($request, $id);
        NotificationHelper::create(__('invoice_edited'), route('invoices.read', $id));

		redirect()->route('invoices.read', $id)->go();
	}
    
    /**
     * download
     *
     * @param  \App\Database\Repositories\Users $users
     * @param  int $id
     * @return void
     */
    public function download(Users $users, string $id): void
    {
        $filename = __('invoice') . '_' . date('Y-m-d') . '_' . time() . '.pdf';

        $data = $this->invoices->findSingleByCompany($id);
        $sender = $users->findSingle(1);
        $sender->country = Countries::countryName($sender->country);
        $recipient = $users->findSingle($data->user_id);
        $recipient->country = Countries::countryName($recipient->country);

        $products = json_decode('[' . $data->products . ']');
        $data->products = $products;

        DownloadHelper::init($filename)->sendPDF(View::getContent('pdf/invoice', [
            'data' => $data,
            'sender' => $sender,
            'recipient' => $recipient
        ]));
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
            if (!$this->invoices->deleteIfExists($id)) {
                redirect()->back()->go();
			}

            redirect()->route('invoices.index')->go();
		} else {
            $this->invoices->deleteBy('id', 'in', explode(',', $request->items));
            response()->json(['redirect' => route('invoices.index')]);
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
        $invoices = $this->invoices->findAllDateRange($request->date_start, $request->date_end);
        $filename = 'invoices_' . date('Y_m_d_His') . '.' . $request->file_type;

		Report::generate($filename, $invoices, [
			//
		]);
	}
}
