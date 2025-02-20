<?php

namespace Core\Support;

use Core\Http\Cookies;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Session;

class UseCase
{
    public function __construct(
        public Request $request,
        public Response $response,
        public Session $session,
        public Cookies $cookies
    ) {
    }
}