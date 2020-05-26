<?php

namespace App\Controllers;

use App\Database\Models\CommentsModel;
use Framework\Core\Controller;
use App\Database\Models\PostsModel;

/**
 * AdminController
 * 
 * Home page controller
 */
class AdminController extends Controller
{
	/**
	 * display home page
	 *
	 * @return void
	 */
	public function posts(): void
	{
		$posts = new PostsModel();

		$this->renderView('admin/posts', [
            'page_title' => 'The Mount Everest Blog - Dashboard | Posts',
            'page_description' => 'Posts administration dashboard',
            'posts' => $posts->paginate(3)
        ]);
	}

	public function comments(): void
	{
		$comments = new CommentsModel();

		$this->renderView('admin/comments', [
            'page_title' => 'The Mount Everest Blog - Dashboard | Comments',
            'page_description' => 'Posts administration dashboard',
            'comments' => $comments->paginate(3)
        ]);
	}
}
