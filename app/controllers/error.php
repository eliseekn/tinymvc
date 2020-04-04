<?php

class ErrorController extends Controller {

	public function error_404() {
		$data = array();
		$data['page_title'] = "Error 404 - Page not found";
		$data['page_description'] = "The page you've requested doesn't exists on this server";

		$this->render("error_404", $data);
	}
}
