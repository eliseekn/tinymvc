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

//url routing and class loader
class Router {

    private static $url = array();
    private static $params = array();
    private static $controller = 'home';
    private static $method = 'index';

    public static function dispatch() {
        //check for $_GET request
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            self::$url = explode('/', $_GET['url']);
        }
        //retrieves controller name as first parameter
        if (isset(self::$url[0])) {
            self::$controller = self::$url[0];
            unset(self::$url[0]);
        }

        //redirect to plugins directory
        if (self::$controller === 'plugins') {
            if (isset(self::$url[1])) {
                $plugin_url = ROOT .'plugins/'. self::$url[1];

                if (is_dir($plugin_url)) {
                    header('Location: '. $plugin_url);
                    exit();
                }
            }
        }

        //return a 404 error if controller filename not found
        if (!file_exists('app/controllers/'. self::$controller .'.php')) {
            require_once 'app/controllers/error.php';

            self::$controller = new ErrorController();
            self::$controller->error_404();

            exit();
        }

        //inclue controller filename
        require_once 'app/controllers/'. self::$controller .'.php';

        //load controller class
        $controller = ucfirst(self::$controller) .'Controller';
        self::$controller = new $controller();

        //retrieves method name as second parameter
        if (isset(self::$url[1])) {
            if (method_exists(self::$controller, self::$url[1])) {
                self::$method = self::$url[1];
                unset(self::$url[1]);
            }
        }

        //set parameters
        self::$params = self::$url ? self::$url : array();

        //add $_POST request as parameter
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                self::$params[] = $value;
            }
        }

        //execute controller with method and parameter
        call_user_func_array([self::$controller, self::$method], self::$params);
    }
}
