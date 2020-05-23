<?php

namespace App\Http\Controllers;

use Framework\Core\Controller;

/**
 * HomeController
 * 
 * Home page controller
 */
class HomeController extends Controller
{
	/**
	 * display home page
	 *
	 * @return void
	 */
	public function index(): void
	{
		$this->renderView('home', [
			'page_title' => 'TinyMVC - Just a PHP framework based on MVC architecture',
			'page_description' => 'TinyMVC is a PHP framework based on MVC architecture'
		]);
	}
}
