<?php

namespace App\Controllers;

use Framework\Core\Controller;
use Framework\Http\Response;

/**
 * AdminController
 * 
 * Home page controller
 */
class AdminController extends Controller
{
	/**
	 * display home page
	 *
	 * @return void
	 */
	public function index(): void
	{
		Response::send([], 'This is admin page')->andStop();
	}
}
