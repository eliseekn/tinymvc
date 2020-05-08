<?php

class AddController
{
	public function __construct()
	{
		$this->items = load_model('items');
	}

	public function index(): void
	{
		$data = HttpRequests::data();

		if (!$data) {
			HttpResponses::send(array(), 'Invalid data', 400);
			exit();
		}

		$data = json_decode($data, true);
		$this->items->add($data['name'], $data['surname']);
		HttpResponses::send(array(), 'Item added successfully', 200);
	}
}
