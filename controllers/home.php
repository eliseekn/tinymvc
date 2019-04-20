<?php

require_once "core/config.php";
require_once "core/controller.php";

class HomeController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$data = [];
		$data['title'] = "TinyMVC - Just a tiny PHP Framework based on MVC architecture";

		$this->view->render("home", $data);
	}
}
