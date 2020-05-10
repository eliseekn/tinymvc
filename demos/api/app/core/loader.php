<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Files and classes loader
 */

/**
 * load helpers by name
 *
 * @param  string $names names of helpers file as enumerated string
 * @return void
 */
function load_helpers(string ...$names): void
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
 * @param  string $name name of controller file
 * @return void
 */
function load_controller(string $name)
{
    $controller = 'app/controllers/' . $name . '.php';

    if (!file_exists($controller)) {
        return null;
    }

    //include controller filename
    require_once $controller;

    $controller = ucfirst($name) . 'Controller';
    return new $controller();
}

/**
 * load model by name
 *
 * @param  string $name name of model file
 * @return void
 */
function load_model(string $name)
{
    $model = 'app/models/' . $name . '.php';

    if (!file_exists($model)) {
        return null;
    }

    //include model filename
    require_once $model;

    $model = ucfirst($name) . 'Model';
    return new $model();
}

/**
 * load page template
 *
 * @param  string $template name of template file
 * @param  string $layout name of layout file
 * @param  array $data variables to include in page
 * @return void
 */
function load_template(string $template, string $layout, array $data = []): void
{
    ob_start();

    //include variables into file
    extract($data);

    //load page template
    require_once 'app/views/templates/' . $template  . '.php';

    //retrieves page content
    $page_content = ob_get_clean();

    //display page layout
    require_once 'app/views/layouts/' . $layout  . '.php';
}