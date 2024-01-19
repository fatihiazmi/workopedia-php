<?php

namespace Framework;

use App\Controllers\ErrorController;
use Framework\Middleware\Authorize;

class Router
{
    protected $routes = [];
    /**
     * Adds a route
     *
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return void
     */
    public function registerRoutes($method, $uri, $action, $middleware = [])
    {
        list($controller, $controllerMethod) = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middleware' => $middleware,
        ];
    }

    /**
     * Add a get route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller, $middleware = [])
    {
        $this->registerRoutes('GET', $uri, $controller, $middleware);
    }
    /**
     * Add a post route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller, $middleware = [])
    {
        $this->registerRoutes('POST', $uri, $controller, $middleware);
    }
    /**
     * Add a put route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller, $middleware = [])
    {
        $this->registerRoutes('PUT', $uri, $controller, $middleware);
    }
    /**
     * Add a delete route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller, $middleware = [])
    {
        $this->registerRoutes('DELETE', $uri, $controller, $middleware);
    }

    /**
     * Route the request
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function route($uri)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {

            $uriSegments = explode('/', trim($uri, '/'));

            $routeSegments = explode('/', trim($route['uri'], '/'));

            $match = true;

            // Check if the number of segments matches
            if (count($uriSegments) === count($routeSegments) && strtoupper($route['method'] === $requestMethod)) {
                $params = [];
                $match = true;

                for ($i = 0; $i < count($uriSegments); $i++) {
                    // If the uri does not match and there is no parameter
                    if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }
                    // Check for parameter and add it to the parameter array
                    if (preg_match('/\{(.+?)}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }

                if ($match) {

                    foreach ($route['middleware'] as $middleware) {
                        (new Authorize())->handle($middleware);
                    }

                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    $controllerInstance = new $controller;
                    $controllerInstance->$controllerMethod($params);

                    return;
                }
            }
        }

        ErrorController::notFound();
    }
}
