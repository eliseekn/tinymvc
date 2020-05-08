<?php

class HomeController
{
	public function __construct()
	{
		$this->posts = load_model('posts');
	}
	
	public function index(int $page = 1): void
	{
		$pagination = generate_pagination($page, $this->posts->total_posts(), 5);

		load_template(
			'home',
			'main',
			array(
				'page_title' => 'The Mount Everest Blog',
				'page_description' => 'Blog about mountaineering',
				'posts' => $this->posts->get_posts(5, $pagination['first_item']),
				'pagination' => array(
					'page' => $pagination['page'],
					'total_pages' => $pagination['total_pages'],
					'previous_page' => $pagination['page'] - 1,
					'next_page' => $pagination['page'] + 1
				)
			)
		);
	}
}
