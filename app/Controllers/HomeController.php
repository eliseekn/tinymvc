<?php

namespace App\Controllers;

use App\Database\Models\PostsModel;
use Framework\Core\Controller;

/**
 * HomeController
 * 
 * Home page controller
 */
class HomeController extends Controller
{
	/**
	 * display home page
	 *
	 * @return void
	 */
	public function index(): void
	{
		$posts = new PostsModel();

		$this->renderView('blog/home', [
			'page_title' => 'The Mount Everest Blog',
			'page_description' => 'Blog about mountaineering',
			'posts' => $posts->paginate(3)
		]);
	}
}
