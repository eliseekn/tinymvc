<?php

namespace App\Controllers;

use App\Database\Models\CommentsModel;
use Framework\Http\Request;
use Framework\Core\Controller;
use Framework\Http\Redirect;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->comments = new CommentsModel();
    }

    public function add(int $post_id)
    {
        $request = new Request();
        $author = $request->getInput('author');
        $content = $request->getInput('content');
        
        $this->comments->setData([
            'post_id' => $post_id,
            'author' => $author,
            'content' => $content
        ])->save();

        Redirect::back()->only();
    }

    public function delete(int $id)
    {
        $this->comments->delete($id);
        Redirect::back()->only();
    }
}