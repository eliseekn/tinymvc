<?php

namespace App\Controllers;

use Framework\Core\View;

/**
 * ExampleController
 */
class ExampleController
{
	/**
	 * display a page
	 *
	 * @return void
	 */
	public function index(): void
	{
		View::render('name_of_page', []);
	}
}
