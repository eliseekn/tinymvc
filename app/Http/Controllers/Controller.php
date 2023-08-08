<?php

namespace App\Http\Controllers;

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Validator\Validator;

class Controller
{
    public function __construct(public Request $request, public Response $response) {}

    public function redirect(string $uri, array $queries = []): void
    {
        $this->response->url($uri, $queries)->send();
    }

    public function render(string $view, array $data = []): void
    {
        $this->response->view($view, $data)->send();
    }

    public function back(): void
    {
        $this->response->back()->send();
    }

    public function json(array $data, int $code = 200): void
    {
        $this->response->json($data)->send($code);
    }

    public function data(string $data, int $code = 200): void
    {
        $this->response->data($data)->send($code);
    }

    public function download(string $filename): void
    {
        $this->response->download($filename)->send(200);
    }

    public function validate(array $rules, array $messages = [], array $inputs = []): Validator
    {
        return $this->request->validate($rules, $messages, $inputs);
    }
}
