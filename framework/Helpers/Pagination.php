<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Pagination parameters generator function
 */

/**
 * pagination parameters generator
 *
 * @param  int $page actual page id
 * @param  int $total_items count of all items
 * @param  int $items_per_pages items count per pages
 * @return array returns pagination parameters
 */
function generate_pagination(int $page, int $total_items, int $items_per_pages): array
{
	//get total number of pages
	$total_pages = ceil($total_items / $items_per_pages);

	//set right page id
	if ($page < 1) {
		$page = 1;
	} else if ($page > $total_pages) {
		$page = $total_pages;
	}

	//get first item of page
	$first_item = ($page - 1) * $total_pages;

	//return paramaters
	return array(
		'first_item' => $first_item,
		'page' => $page,
		'total_pages' => $total_pages
	);
}
