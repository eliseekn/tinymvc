<?php

class PostsController
{
	public function __construct()
	{
		$this->posts = load_model('posts');
		$this->comments = load_model('comments');
	}

	public function index(string $slug): void
	{
		$post = $this->posts->get_post_slug($slug);

		load_template(
			'post',
			'main',
			array(
				'page_title' => 'The Mount Everest Blog',
				'page_description' => 'Blog post content',
				'post' => $post,
				'comments' => $this->comments->get_post_comments($post['id']),
				'total_comments' => $this->comments->total_post_comments($post['id'])
			)
		);
	}
}
