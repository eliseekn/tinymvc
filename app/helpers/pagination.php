<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => pagination.php (pagination parameters generator)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//pagination parmeters generator
function generate_pagination(int $page, int $total_items, int $items_per_pages): array {
	//get total number of pages
	$total_pages = ceil($total_items / $items_per_pages);

	//set right page id
	if ($page < 1) {
		$page = 1;
	} elseif ($page > $total_pages) {
		$page = $total_pages;
	}

	//get list first item
	$first_item = ($page - 1) * $total_pages;

	//return pagination paramaters
	return array(
		'first_item' => $first_item,
		'page' => $page, 
		'total_pages' => $total_pages
	);
}
