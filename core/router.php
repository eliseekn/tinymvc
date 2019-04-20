<?php

class Router {

    private $url = [];
    private $params = [];
    private $controller = "home";
    private $action = "index";

    public function route() {
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $this->url = explode('/', $_GET['url']);
        }

        if (isset($this->url[0])) {
            $this->controller = $this->url[0];
            unset($this->url[0]);
        }

        if (!file_exists("controllers/".$this->controller.".php")) {
            require_once "controllers/error.php";

            $this->controller = new ErrorController();
            $this->controller->error_404();

            exit();
        }

        require_once "controllers/".$this->controller.".php";

        $controller = ucfirst(strtolower($this->controller))."Controller";
        $this->controller = new $controller();

        if (isset($this->url[1])) {
            if (method_exists($this->controller, $this->url[1])) {
                $this->action = $this->url[1];
                unset($this->url[1]);
            }
        }

        $this->params = $this->url ? $this->url : [];

        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->params[] = $value;
            }
        }

        call_user_func_array([$this->controller, $this->action], $this->params);
    }
}
