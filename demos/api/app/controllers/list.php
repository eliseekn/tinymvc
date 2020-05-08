<?php

class ListController
{
	public function __construct()
	{
		$this->items = load_model('items');
	}

	public function index(): void
	{
		$data = $this->items->list();
		$data = json_encode($data);

		HttpResponses::send(
			array(
				'Content-Type' => 'application/json'
			), 
			$data, 
			200);
	}
}
