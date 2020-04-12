<?php

class HomeController {

	public function index() {
		$data['page_title'] = 'TinyMVC - Just a tiny PHP Framework based on MVC architecture';
		$data['page_description'] = 'TinyMVC is a PHP Framework based on MVC architecture';

		load_template('home', 'main', $data);
	}
}
