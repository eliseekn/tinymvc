<?php

namespace App\Controllers;

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
			'page_title' => 'The Mount Everest Blog',
			'page_description' => 'Blog about mountaineering'
		]);
	}
}
