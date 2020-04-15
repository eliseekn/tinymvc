<?php

class HomeController {

	public function index() {
		load_template('home', 'main', 
			array(
				'page_title' => 'TinyMVC - Just a tiny PHP Framework based on MVC architecture',
				'page_description' => 'TinyMVC is a PHP Framework based on MVC architecture'
			)
		);
	}
}
