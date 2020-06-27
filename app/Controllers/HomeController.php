<?php

namespace App\Controllers;

use Framework\Routing\View;

class HomeController
{
	/**
	 * display home page
	 *
	 * @return void
	 */
	public function index(): void
	{
		View::render('index', [
			//
		]);
	}
}
