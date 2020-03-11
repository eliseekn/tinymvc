<?php
require_once "app/core/controller.php";

class ErrorController extends Controller {

	public function error_404() {
		$data = array();
		$data['page_title'] = "Error 404 - Page not found";

		$this->render("error_404", $data);
	}
}
