<?php

namespace App\Http\Controllers;


class HomeController
{
	/**
	 * index
	 *
	 * @return void
	 */
	public function index(): void
	{
        render('index');
	}
}
