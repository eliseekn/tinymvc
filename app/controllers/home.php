<?php

require_once "app/core/config.php";
require_once "app/core/controller.php";

class HomeController extends Controller {

	public function index() {
		$data = [];
		$data['page_title'] = "TinyMVC - Just a tiny PHP Framework based on MVC architecture";

		$this->render("home", $data);
	}
}
