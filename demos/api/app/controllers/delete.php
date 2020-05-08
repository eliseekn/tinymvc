<?php

class DeleteController
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
		$this->items->delete($data['id']);
		HttpResponses::send(array(), 'Item deleteed successfully', 200);
	}
}
