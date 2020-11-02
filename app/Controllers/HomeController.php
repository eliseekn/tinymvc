<?php

namespace App\Controllers;

use Framework\Routing\Controller;

class HomeController extends Controller
{
	/**
	 * display home page
	 *
	 * @return void
	 */
	public function index(): void
	{
        $this->render('index', [
			//
        ]);
	}
}
