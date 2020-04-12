<?php

/**
* TinyMVC
*
* MIT License
*
* Copyright (c) 2019, N'Guessan Kouadio Elisée
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* @author: N'Guessan Kouadio Elisée AKA eliseekn
* @contact: eliseekn@gmail.com - https://eliseekn.netlify.app
* @version: 1.0.0.0
*/

//generate pagination paramaters

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
