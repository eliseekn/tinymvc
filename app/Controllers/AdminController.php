<?php

namespace App\Controllers;

use Framework\Core\Controller;

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
		echo 'This is adminitration page';
	}
}
