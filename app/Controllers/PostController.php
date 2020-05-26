<?php

namespace App\Controllers;

use App\Database\Models\CommentsModel;
use App\Database\Models\PostsModel;
use Framework\Core\Controller;
use Framework\Http\Redirect;
use Framework\Http\Request;

/**
 * PostController
 * 
 * Post page controller
 */
class PostController extends Controller
{
	public function __construct()
	{
		$this->posts = new PostsModel();
		$this->comments = new CommentsModel();
		$this->request = new Request();
	}

	/**
	 * display home page
	 *
	 * @return void
	 */
	public function index(string $slug): void
	{
		$post = $this->posts->get($slug);

		$this->renderView('post', [
			'page_title' => 'The Mount Everest Blog | ' . $post->title,
			'header_title' => 'The Mount Everest Blog',
            'page_description' => 'Blog about mountaineering',
			'post' => $post,
			'comments' => $this->comments->get($post->id)
		]);
	}

	public function add(): void
	{
		$image = $this->request->getFile('image');
		$image->moveTo(absolute_path('tinymvc/public/assets/img/posts'));

		$this->posts->setData([
			'title' => $this->request->getInput('title'),
			'slug' => generate_slug($this->request->getInput('title')),
			'image' => $image->getOriginalFilename(),
			'content' => $this->request->getInput('content')
		])->save();

		Redirect::back()->only();
	}

	public function edit(int $id): void
	{
		$this->posts->setData([
			'title' => $this->request->getInput('title'),
			'slug' => generate_slug($this->request->getInput('title')),
			'content' => $this->request->getInput('content')
		])->update($id);

		Redirect::back()->only();
	}

	public function replaceImage(int $post_id): void
	{
		$post = $this->posts->find($post_id);
		unlink(absolute_path('tinymvc/public/assets/img/posts/' . $post->image));

		$image = $this->request->getFile('image');
		$image->moveTo(absolute_path('tinymvc/public/assets/img/posts'));

		$this->posts->setData([
			'image' => $image->getOriginalFilename()
		])->update($post_id);

		Redirect::back()->only();
	}

	public function delete(int $id): void
	{
		$post = $this->posts->find($id);
		unlink(absolute_path('tinymvc/public/assets/img/posts/' . $post->image));
		$this->posts->delete($id);

		Redirect::back()->only();
	}
}