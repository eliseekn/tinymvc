<?php

class ErrorController
{
	public function error_404()
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
