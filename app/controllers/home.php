<?php

class HomeController
{
	public function index()
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
