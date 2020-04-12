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

//url routing and dispatching
class Router {

    private $url = array();
    private $params = array();
    private $controller = 'home'; //default application controller
    private $method = 'index'; //default controller method

    public function __construct() {
        //get url parameters
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $this->url = explode('/', $_GET['url']);
        }
    }

    public function dispatch() {
        //retrieves controller name as first parameter
        if (isset($this->url[0])) {
            $this->controller = $this->url[0];
            unset($this->url[0]);
        }

        //redirect to plugins directory
        if ($this->controller === 'plugins') {
            if (isset($this->url[1])) {
                $plugin = '../../plugins/'. $this->url[1];

                if (is_dir($plugin)) {
                    header('Location: '. $plugin);
                    exit();
                }
            }
        }

        //load controller class
        $this->controller = load_controller($this->controller);

        //return a 404 error if controller filename not found
        if ($this->controller === NULL) {
            $error_controller = load_controller('error');
            $error_controller->error_404();
            exit();
        }

        //retrieves method name as second parameter
        if (isset($this->url[1])) {
            if (method_exists($this->controller, $this->url[1])) {
                $this->method = $this->url[1];
                unset($this->url[1]);
            }
        }

        //set parameters
        $this->params = $this->url ? $this->url : array();

        //add $_POST request as parameters
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->params[] = $value;
            }
        }

        //execute controller with method and parameter
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
}
