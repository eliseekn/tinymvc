<?php

class ErrorController
{
	public function error_404(): void
	{
		load_template(
			'error_404',
			'error',
			array(
				'page_title' => 'Error 404 - Page not found',
				'page_description' => 'Page requested not found',
				'footer_title' => 'The Mount Everest Blog'
			)
		);
	}
}
