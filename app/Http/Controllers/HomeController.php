<?php

namespace App\Http\Controllers;

use Framework\Routing\Controller;

class HomeController extends Controller
{
	/**
	 * index
	 *
	 * @return void
	 */
	public function index(): void
	{
        $this->render('index');
	}
}
