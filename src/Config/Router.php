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

        if ($requestMethod === 'OPTIONS') {
            // Handle OPTIONS request
            $this->sendOptionsResponse();
            return;
        }

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
        // Start parameters array with the request
        $parameters = ['request' => $request];

        // Extract route parameters from $_GET
        foreach ($_GET as $key => $value) {
            if ($key !== 'request') {
                if (is_numeric($value)) {
                    $parameters[$key] = (int) $value;
                } else {
                    $parts = explode('/', $value);
                    $lastPart = end($parts);

                    if (is_numeric($lastPart)) {
                        $parameters[$key] = (int) $lastPart;
                    }
                }
            }
        }

        if (is_callable($callback)) {
            $response = call_user_func_array($callback, $parameters);
        } elseif (is_array($callback) && count($callback) === 2 && is_callable([$callback[0], $callback[1]])) {
            $controller = new $callback[0]();
            $action = $callback[1];
            $response = call_user_func_array([$controller, $action], $parameters);
        }

        if (!$response instanceof Response) {
            $response = new Response($response);
        }

        $response->headers->set('Content-Type', 'application/json');

        // Set the CORS headers
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token');

        $response->send();
    }

    private function sendOptionsResponse(): void
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token');
        $response->send();
    }
}
