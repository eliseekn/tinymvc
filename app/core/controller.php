<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Controller
 * 
 * Main controller class extended from Model class
 */
class Controller extends Model
{    
    /**
     * initialize class from model
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
}
