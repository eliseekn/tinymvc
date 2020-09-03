<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Pagination parameters generator function
 */

if (!function_exists('generate_pagination')) {
	/**
	 * pagination parameters generator
	 *
	 * @param  int $page
	 * @param  int $total_items
	 * @param  int $items_per_pages
	 * @return array returns pagination parameters
	 */
	function generate_pagination(int $page, int $total_items, int $items_per_pages): array
	{
		//get total number of pages
		$total_pages = $items_per_pages > 0 ? ceil($total_items / $items_per_pages) : 1;

		//get first item of page (offset)
		$first_item = ($page - 1) * $items_per_pages;

		//return paramaters
		return [
			'first_item' => $first_item,
			'total_items' => $total_items,
			'items_per_pages' => $items_per_pages,
			'page' => $page,
			'total_pages' => $total_pages
		];
	}
}
