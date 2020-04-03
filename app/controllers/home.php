<?php

class HomeController extends Controller {

	public function index() {
		$data = array();
		$data['page_title'] = 'TinyMVC - Just a tiny PHP Framework based on MVC architecture';
		$data['page_description'] = 'TinyMVC is a PHP Framework based on MVC architecture';

		$this->render('home', $data);
	}
}
