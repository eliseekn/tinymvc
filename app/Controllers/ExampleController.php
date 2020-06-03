<?php

namespace App\Controllers;

use Framework\Core\Controller;

/**
 * ExampleController
 * 
 */
class ExampleController extends Controller
{
	/**
	 * display a page
	 *
	 * @return void
	 */
	public function index(): void
	{
		$this->renderView('name_of_page', []);
	}
}
