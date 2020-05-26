<?php

namespace App\Database\Models;

use Framework\ORM\Model;

/**
 * CommentsModel
 * 
 * Comments model class
 */
class CommentsModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * instantiates class
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }

    /**
     * get post row by slug
     *
     * @param  string $slug post slug
     * @return void
     */
    public function get(int $post_id)
    {
        return $this->findAllWhere('post_id', '=', $post_id);
    }
}