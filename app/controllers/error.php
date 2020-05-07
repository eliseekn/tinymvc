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

/**
 * ErrorController
 * 
 * Error controller class
 */
class ErrorController
{	
	/**
	 * display 404 error page
	 *
	 * @return void
	 */
	public function error_404(): void
	{
		load_template(
			'error_404',
			'main',
			array(
				'page_title' => 'Error 404 - Page not found',
				'page_description' => 'The page you have requested does not exists on this server'
			)
		);
	}
}
