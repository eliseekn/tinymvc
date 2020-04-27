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
 * Files and classes loader
 */

/**
 * load helpers by name
 *
 * @param  mixed $names
 * @return void
 */
function load_helpers(...$names): void
{
    if (!empty($names)) {
        foreach ($names as $name) {
            $helper = 'app/helpers/' . $name . '.php';

            //include helper filename
            if (file_exists($helper)) {
                require_once $helper;
            }
        }
    }
}

/**
 * load controller by name
 *
 * @param  string $name
 * @return void
 */
function load_controller(string $name)
{
    $controller = 'app/controllers/' . $name . '.php';

    if (!file_exists($controller)) {
        return NULL;
    }

    //import controller filename
    require_once $controller;

    $controller = ucfirst($name) . 'Controller';
    return new $controller();
}

/**
 * load model by name
 *
 * @param  string $name
 * @return void
 */
function load_model(string $name)
{
    $model = 'app/models/' . $name . '.php';

    if (!file_exists($model)) {
        return NULL;
    }

    //import model filename
    require_once $model;

    $model = ucfirst($name) . 'Model';
    return new $model();
}

/**
 * load page template
 *
 * @param  string $template
 * @param  string $layout
 * @param  array $data
 * @return void
 */
function load_template(string $template, string $layout, array $data = []): void
{
    ob_start();

    //set data variables
    if (!empty($data)) {
        extract($data);
    }

    //include page content
    require_once 'app/views/templates/' . $template  . '.php';

    //retrieves page content
    $page_content = ob_get_clean();

    //display all
    require_once 'app/views/layouts/' . $layout  . '.php';
}
