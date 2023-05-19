<?php

namespace App\Config;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    private array $routes = [];

    public function get(string $path, $callback): void
    {
        $this->addRoute('GET', $path, $callback);
    }

    public function post(string $path, $callback): void
    {
        $this->addRoute('POST', $path, $callback);
    }

    public function put(string $path, $callback): void
    {
        $this->addRoute('PUT', $path, $callback);
    }

    public function delete(string $path, $callback): void
    {
        $this->addRoute('DELETE', $path, $callback);
    }

    private function addRoute(string $method, string $path, $callback): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = $_SERVER['REQUEST_URI'];

        $matchedRoute = null;

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchPath($route['path'], $requestPath)) {
                $matchedRoute = $route;
                break;
            }
        }

        if ($matchedRoute) {
            $this->executeCallback($matchedRoute['callback'], new Request());
        } else {
            // Handle 404 Not Found
            http_response_code(404);
            echo '404 Not Found';
        }
    }

    private function matchPath(string $routePath, string $requestPath): bool
    {
        $routePath = rtrim($routePath, '/');
        $requestPath = rtrim($requestPath, '/');

        $routeSegments = explode('/', $routePath);
        $requestSegments = explode('/', $requestPath);

        if (count($routeSegments) !== count($requestSegments)) {
            return false;
        }

        $params = [];

        for ($i = 0; $i < count($routeSegments); $i++) {
            $routeSegment = $routeSegments[$i];
            $requestSegment = $requestSegments[$i];

            if ($routeSegment !== $requestSegment && !preg_match('/{(.+)}/', $routeSegment)) {
                return false;
            }

            if (preg_match('/{(.+)}/', $routeSegment, $matches)) {
                $paramName = $matches[1];
                $paramValue = urldecode($requestSegment);
                $params[$paramName] = $paramValue;
            }
        }

        $_GET = array_merge($_GET, $params);

        return true;
    }

    private function executeCallback($callback, Request $request): void
    {
        if (is_callable($callback)) {
            $response = call_user_func($callback, $request);
        } elseif (is_array($callback) && count($callback) === 2 && is_callable([$callback[0], $callback[1]])) {
            $controller = new $callback[0]();
            $action = $callback[1];
            $response = $controller->$action($request);
        }

        if (!$response instanceof Response) {
            $response = new Response($response);
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->send();
    }
}
