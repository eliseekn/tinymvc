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

require_once 'config.php';

class Router {

    private $url = array();
    private $params = array();
    private $controller = 'home';
    private $method = 'index';

    public function load_core() {
        require_once 'controller.php';
        require_once 'model.php';
        require_once 'view.php';
        require_once 'router.php';
        require_once 'database.php';
    }

    public function load_helpers(...$helpers) {
        if (!empty($helpers)) {
            foreach ($helpers as $helper) {
                $helper = 'app/helpers/'. $helper .'.php';

                if (file_exists($helper)) {
                    require_once $helper;
                }
            }
        }
    }

    public function dispatch() {
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $this->url = explode('/', $_GET['url']);
        }

        if (isset($this->url[0])) {
            $this->controller = $this->url[0];
            unset($this->url[0]);
        }

        if ($this->controller === 'plugins') {
            if (isset($this->url[1])) {
                $plugin_url = ROOT .'plugins/'. $this->url[1];

                if (is_dir($plugin_url)) {
                    header('Location: '. $plugin_url);
                    exit();
                }
            }
        }

        if (!file_exists('app/controllers/'. $this->controller .'.php')) {
            require_once 'app/controllers/error.php';

            $this->controller = new ErrorController();
            $this->controller->index();

            exit();
        }

        require_once 'app/controllers/'. $this->controller .'.php';

        $controller = ucfirst($this->controller) .'Controller';
        $this->controller = new $controller();

        if (isset($this->url[1])) {
            if (method_exists($this->controller, $this->url[1])) {
                $this->method = $this->url[1];
                unset($this->url[1]);
            }
        }

        $this->params = $this->url ? $this->url : array();

        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->params[] = $value;
            }
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }
}
