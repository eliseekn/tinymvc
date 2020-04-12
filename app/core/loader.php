<?php

/**
* TinyMVC
*
* MIT License
*
* Copyright (c) 2019, N'Guessan Kouadio Elisée
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* @author: N'Guessan Kouadio Elisée AKA eliseekn
* @contact: eliseekn@gmail.com - https://eliseekn.netlify.app
* @version: 1.0.0.0
*/

//class loader

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
    if (is_array($data) && !empty($data)) {
        extract($data);
    }

    //include page content
    require_once 'app/views/templates/'. $template  .'.php';

    //retrieves page content
    $page_content = ob_get_clean();

    //display all
    require_once 'app/views/layouts/'. $layout  .'.php';
}
