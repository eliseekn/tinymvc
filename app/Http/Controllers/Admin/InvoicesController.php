<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\Report;
use App\Helpers\Activity;
use App\Helpers\Countries;
use Framework\Http\Request;
use Framework\Routing\View;
use Framework\Support\Alert;
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
        $paid_count = $this->invoices->invoicesCount('paid', $user_id);
        $pending_count = $this->invoices->invoicesCount('pending', $user_id);
        $expired_count = $this->invoices->invoicesCount('expired', $user_id);

		$this->render('admin.invoices.index', compact('data', 'paid_count', 'pending_count', 'expired_count'));
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
        $data = $this->invoices->findOneBy($id);
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
        $data = $this->invoices->findOneWithCompany($id);
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
        
        Activity::log(__('invoice_created'));
		redirect()->route('invoices.read', $id)->withToast('success', __('invoice_created'))->go();
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

        Activity::log(__('invoice_updated'));
		redirect()->route('invoices.read', $id)->withToast('success', __('invoice_updated'))->go();
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
        $filename = strtolower(__('invoice')) . '_' . date('Y-m-d') . '_' . time() . '.pdf';

        $data = $this->invoices->findOneWithCompany($id);
        $sender = $users->findOne(1);
        $sender->country = Countries::countryName($sender->country);
        $recipient = $users->findOne($data->user_id);
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
        $this->invoices->flush($request, $id);

		if (!is_null($id)) {
            Activity::log(__('invoice_deleted'));
            $this->redirect()->route('invoices.index', Auth::get('id'))->withToast('success', __('invoice_deleted'))->go();
		} else {
            Activity::log(__('invoices_deleted'));
            Alert::toast(__('invoices_deleted'))->success();
            $this->response()->json(['redirect' => route('invoices.index', Auth::get('id'))]);
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
			'company' => __('company'), 
            'invoice_id' => __('invoice'),
            'phone' => __('phone'),
            'sub_total' => __('sub_total'),
			'tax' => __('tax'), 
			'total_price' => __('total_price'), 
			'status' => __('status'), 
			'created_at' => __('created_at'),
			'expires_at' => __('expires_at')
		]);
	}
}
