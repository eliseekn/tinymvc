<?php

class DashboardController
{
	public function __construct()
	{
		$this->posts = load_model('posts');
		$this->comments = load_model('comments');
	}

	public function index(int $page = 1): void
	{
		if (!session_exists('admin')) {
			redirect_to('admin');
		}

		$pagination = generate_pagination($page, $this->posts->total_posts(), 3);

		load_template(
			'dashboard/posts',
			'dashboard',
			array(
				'page_title' => 'The Mount Everest Blog - Dashboard | Posts',
				'page_description' => 'Posts administration dashboard',
				'posts' => $this->posts->get_posts(3, $pagination['first_item']),
				'pagination' => array(
					'page' => $pagination['page'],
					'total_pages' => $pagination['total_pages'],
					'previous_page' => $pagination['page'] - 1,
					'next_page' => $pagination['page'] + 1
				)
			)
		);
	}

	public function comments(int $page = 1): void
	{
		if (!session_exists('admin')) {
			redirect_to('admin');
		}

		$pagination = generate_pagination($page, $this->comments->total_comments(), 10);

		load_template(
			'dashboard/comments',
			'dashboard',
			array(
				'page_title' => 'The Mount Everest Blog - Dashboard | Comments',
				'page_description' => 'Comments administration dashboard',
				'comments' => $this->comments->get_comments(10, $pagination['first_item']),
				'pagination' => array(
					'page' => $pagination['page'],
					'total_pages' => $pagination['total_pages'],
					'previous_page' => $pagination['page'] - 1,
					'next_page' => $pagination['page'] + 1
				)
			)
		);
	}

	public function add_post(): void
	{
		$title = HttpRequests::post('post-title');
		$content = HttpRequests::post('post-content');

		if (!empty($title) && !empty($content)) {
            $title = sanitize_input($title);
			$content = sanitize_input($content);
			$slug = generate_slug($title);

			if (upload_file('post-image', DOCUMENT_ROOT . 'tinymvc/demos/blog/public/assets/img/posts', false, $image)) {
				$this->posts->add_post($title, $slug, $image, $content);
			}
        }
		
		redirect_to('dashboard/posts');
	}

	public function edit_post(int $id): void
	{
		$title = HttpRequests::post('post-title');
		$content = HttpRequests::post('post-content');

		if (!empty($title) || !empty($content)) {
            $title = sanitize_input($title);
			$content = sanitize_input($content);
			$slug = generate_slug($title);

			$this->posts->edit_post($id, $title, $slug, $content);
        }
	}

	public function edit_post_image(int $post_id): void
	{
		if (upload_file('post-image', DOCUMENT_ROOT . 'tinymvc/demos/blog/public/assets/img/posts', false, $image)) {
			$this->posts->edit_post_image($post_id, $image);
		}
	}

	public function delete_post(int $id): void
	{
		$this->posts->delete_post($id);
	}

	public function add_comment(int $post_id): void
	{
		$author = HttpRequests::post('author');
		$content = HttpRequests::post('content');

		if (!empty($author) || !empty($content)) {
			$author = sanitize_input($author);
			$content = sanitize_input($content);

			$this->comments->add_comment($author, $content, $post_id);
		}
	}

	public function delete_comment(int $id): void
	{
		$this->comments->delete_comment($id);
	}
}
