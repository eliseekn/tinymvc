<?php

class PostsModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_posts(int $limit, int $offset): array
    {
        return $this->select('*')
            ->from('posts')
            ->order_by('id', 'DESC')
            ->limit($limit, $offset)
            ->fetch_all();
    }

    public function get_post_slug(string $slug): array
    {
        return $this->select('*')
            ->from('posts')
            ->where('slug', '=', $slug)
            ->fetch();
    }

    public function get_post_id(int $id): array
    {
        return $this->select('*')
            ->from('posts')
            ->where('id', '=', $id)
            ->fetch();
    }
    
    public function add_post(string $title, string $slug, string $image, string $content): void
    {
        $this->insert(
            'posts',
            array(
                'title' => $title,
                'slug' => $slug,
                'image' => $image,
                'content' => $content
            )
        )->execute_query();
    }
    
    public function edit_post(int $id, string $title, string $slug, string $content): void
    {
        $this->update('posts')
            ->set(
                array(
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $content
                )
            )
            ->where('id', '=', $id)
            ->execute_query();
    }
    
    public function edit_post_image(int $post_id, string $image): void
    {
        $post = $this->get_post_id($post_id);
        unlink(DOCUMENT_ROOT . 'demos/blog/public/assets/img/posts/' . $post['image']);

        $this->update('posts')
            ->set(
                array(
                    'image' => $image
                )
            )
            ->where('id', '=', $post_id)
            ->execute_query();
    }
    
    public function delete_post(int $id): void
    {
        $post = $this->get_post_id($id);
        unlink(DOCUMENT_ROOT . 'demos/blog/public/assets/img/posts/' . $post['image']);

        $this->delete_from('posts')
            ->where('id', '=', $id)
            ->limit(1)
            ->execute_query();
    }

    public function total_posts(): int
    {
        return $this->select('*')
            ->from('posts')
            ->rows_count();
    }
}
