<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

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
			'page_title' => 'TinyMVC - Just a PHP framework based on MVC architecture',
			'page_description' => 'TinyMVC is a PHP framework based on MVC architecture'
		]);
	}
}
