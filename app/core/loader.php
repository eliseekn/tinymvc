<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => loader.php (files and classes loader)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//load helpers by name
function load_helpers(...$names) {
    if (!empty($names)) {
        foreach ($names as $name) {
            $helper = 'app/helpers/'. $name .'.php';

            //include helper filename
            if (file_exists($helper)) {
                require_once $helper;
            }
        }
    }
}

//load controller by name
function load_controller(string $name) {
    $controller = 'app/controllers/'. $name .'.php';
    
    if (!file_exists($controller)) {
        return NULL;
    } 
    
    //import controller filename
    require_once $controller;

    $controller = ucfirst($name) .'Controller';
    return new $controller();
}

//load model by name
function load_model(string $name) {
    $model = 'app/models/'. $name .'.php';
    
    if (!file_exists($model)) {
        return NULL;
    }

    //import model filename
    require_once $model;

    $model = ucfirst($name) .'Model';
    return new $model();
}

//load page template
function load_template(string $template, string $layout, array $data = []) {
    ob_start();

    //set data variables
    if (!empty($data)) {
        extract($data);
    }

    //include page content
    require_once 'app/views/templates/'. $template  .'.php';

    //retrieves page content
    $page_content = ob_get_clean();

    //display all
    require_once 'app/views/layouts/'. $layout  .'.php';
}
