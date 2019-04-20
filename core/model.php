<?php

require_once "core/config.php";
require_once "core/database.php";

class Model {

	public function __construct() {
		$this->db = new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	}
}
