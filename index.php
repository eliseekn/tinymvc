<?php

require_once "core/config.php";
require_once "core/router.php";

if (DEV) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

$router = new Router();
$router->route();
