<?php

declare(strict_types=1);

namespace Core\Http\Routing;

use Core\Enums\HttpCode;
use Core\Exceptions\FileNotFoundException;
use Core\Exceptions\InvalidJsonDataException;
use Core\Exceptions\InvalidResponseDataException;
use Core\Http\Cookies;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Session;
use Core\Http\Validation\Validator\Validator;
use Exception;

class Controller
{
    public function __construct(
        public Request $request,
        public Response $response,
        public Session $session,
        public Cookies $cookies
    ) {
    }

    public function redirectToUrl(string $uri, array $queries = []): void
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
        $this->response->view($view, $data)->send(HttpCode::OK);
    }

    /**
     * @throws InvalidResponseDataException
     */
    public function response(string $data, int $code = HttpCode::OK): void
    {
        $this->response->data($data)->send($code);
    }

    /**
     * @throws InvalidJsonDataException
     */
    public function jsonResponse(array $data, int $code = HttpCode::OK): void
    {
        $this->response->json($data)->send($code);
    }

    /**
     * @throws FileNotFoundException
     */
    public function downloadResponse(string $filename, int $code = HttpCode::OK): void
    {
        $this->response->download($filename)->send($code);
    }

    /**
     * @throws Exception
     */
    public function validate(Validator $validator): array
    {
        return $validator
            ->validate($this->request, $this->response)
            ->inputs();
    }
}
