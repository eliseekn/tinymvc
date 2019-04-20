<?php

class Pagination {

	public $total_pages;
	public $first_item;
	public $items_per_pages = 5;

	public function __construct($page_id, $total_items) {
		$this->total_pages = ceil($total_items / $this->items_per_pages);

		if ($page_id < 1) {
			$page_id = 1;
		} elseif ($page_id > $this->total_pages) {
			$page_id = $this->total_pages;
		}

		$this->first_item = ($page_id - 1) * $this->items_per_pages;
	}
}
