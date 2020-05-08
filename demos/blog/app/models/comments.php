<?php

class CommentsModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_comments(int $limit, int $offset): array
    {
        return $this->select('*')
            ->from('comments')
            ->order_by('id', 'DESC')
            ->limit($limit, $offset)
            ->fetch_all();
    }
    
    public function get_post_comments(int $post_id): array
    {
        return $this->select('*')
            ->from('comments')
            ->where('post_id', '=', $post_id)
            ->order_by('id', 'DESC')
            ->fetch_all();
    }
    
    public function add_comment(string $author, string $content, int $post_id): void
    {
        $this->insert(
            'comments',
            array(
                'author' => $author,
                'content' => $content,
                'post_id' => $post_id
            )
        )->execute_query();
    }
    
    public function delete_comment(int $id): void
    {
        $this->delete_from('comments')
            ->where('id', '=', $id)
            ->limit(1)
            ->execute_query();
    }
    
    public function total_post_comments(int $post_id): int
    {
        return $this->select('*')
            ->from('comments')
            ->where('post_id', '=', $post_id)
            ->rows_count();
    }

    public function total_comments(): int
    {
        return $this->select('*')
            ->from('comments')
            ->rows_count();
    }
}
