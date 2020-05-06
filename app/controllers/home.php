<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * HomeController
 * 
 * Home page controller
 */
class HomeController
{	
	/**
	 * display home page
	 *
	 * @return void
	 */
	public function index(): void
	{
		load_template(
			'home',
			'main',
			array(
				'page_title' => 'TinyMVC - Just a PHP framework based on MVC architecture',
				'page_description' => 'TinyMVC is a PHP framework based on MVC architecture'
			)
		);
	}
}
