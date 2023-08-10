<?php

namespace Core\Routing;

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Validator\Validator;

class Controller
{
    public function __construct(public Request $request, public Response $response) {}

    public function redirectUrl(string $uri, array $queries = []): void
    {
        $this->response->url($uri, $queries)->send();
    }

    public function redirectRoute(string $route, array $params = []): void
    {
        $this->response->route($route, $params)->send();
    }

    public function redirectBack(): void
    {
        $this->response->back()->send();
    }

    public function render(string $view, array $data = []): void
    {
        $this->response->view($view, $data)->send(200);
    }

    public function response(string $data, int $code = 200): void
    {
        $this->response->data($data)->send($code);
    }

    public function jsonResponse(array $data, int $code = 200): void
    {
        $this->response->json($data)->send($code);
    }

    public function downloadResponse(string $filename): void
    {
        $this->response->download($filename)->send(200);
    }

    public function validateRequest(Validator $validator): array
    {
        return $validator
            ->validate($this->request->inputs(), $this->response)
            ->validated();
    }
}
