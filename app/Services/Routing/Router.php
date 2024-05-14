<?php

declare(strict_types=1);

namespace App\Services\Routing;

use App\Services\Http\RedirectResponse;
use App\Services\Http\ResponseInterface;
use Exception;

class Router
{
    /**
     * The list of all available routes.
     *
     * @var array|array[]
     */
    public array $routes = [
        'GET' => [],
        'POST' => [],
        'DELETE' => [],
        'PUT' => [],
        'PATCH' => [],
    ];

    /**
     * Loads a route file into cache.
     *
     * @param string $file
     * @return static
     */
    public static function load(string $file): static
    {
        $router = new static();
        require $file;
        return $router;
    }

    /**
     * Handles a GET request.
     *
     * @param string $uri
     * @param string|array $callable
     * @return void
     */

    public function get(string $uri, string|array $callable): void
    {
        $this->routes['GET'][$uri] = $callable;
    }

    /**
     * Handles a POST request.
     *
     * @param string $uri
     * @param string|array $controller
     * @return void
     */
    public function post(string $uri, string|array $controller): void
    {
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * @param string $uri
     * @param string|array $requestType
     * @return void
     * @throws Exception
     */
    public function direct(string $uri, string|array $requestType): void
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            $route = $this->routes[$requestType][$uri];

            if (true === is_array($route)) {
                $this->callAction(
                    ...$route,
                )
                    ->send();
            } else {
                $this->callAction(
                    ...explode('@', $route[$uri])
                )
                    ->send();
            }

            return;
        }

        (new RedirectResponse('/404'))
            ->setStatusCode(404)
            ->send();
    }

    /**
     * Calls a method of a controller, defined by route path.
     *
     * @param string $controller
     * @param string $action
     * @return ResponseInterface
     * @throws Exception
     */
    protected function callAction(string $controller, string $action): ResponseInterface
    {
        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $action)) {
            throw new Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }

        return $controllerInstance->$action();
    }
}
