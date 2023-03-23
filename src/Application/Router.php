<?php

namespace MVC\Application;

use Exception;
use MVC\Application\Constant\HttpMethod;
use MVC\Application\Constant\HttpStatusCode;
use MVC\Application\Response\Response;
use MVC\Application\Response\ResponseInterface;
use MVC\Application\Service\ServiceLocator;
use Throwable;

class Router
{
    protected array $routes = [];
    protected Request $request;

    /**
     * @param array $routes
     *
     * @throws Throwable
     */
    public function __construct(array $routes)
    {
        $this->request = new Request();

        $this->prepareRoutes($routes);
    }

    /**
     * @param string $path
     * @param string $callback
     *
     * @return void
     */
    public function methodGet(string $path, string $callback): void
    {
        $this->routes[HttpMethod::GET][$path] = $callback;
    }

    /**
     * @param string $path
     * @param string $callback
     *
     * @return void
     */
    public function methodPost(string $path, string $callback): void
    {
        $this->routes[HttpMethod::POST][$path] = $callback;
    }

    /**
     * @return mixed
     */
    public function resolve(): ResponseInterface
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $action = $this->routes[$method][$path] ?? false;

        $position = strpos($action, '::');

        $actionMethod = null;

        if ($position !== false) {
            $controller = substr($action, 0, $position);
            $actionMethod = substr($action, $position + 2);
        } else {
            $controller = $action;
            $actionMethod = '__invoke';
        }

        if ($controller === false) {
            return new Response("Route not found!", [], HttpStatusCode::NOT_FOUND);
        }

        $controllerObject = ServiceLocator::getService($controller);

        return $controllerObject->$actionMethod($this->request);
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param array $routes
     *
     * @return void
     *
     * @throws Throwable
     */
    protected function prepareRoutes(array $routes): void
    {
        try {
            foreach ($routes as $route) {
                $method = $route['method'];
                $routePath = $route['route'];
                $action = $route['action'];

                switch (strtoupper($method)) {
                    case HttpMethod::GET:
                        $this->methodGet($routePath, $action);

                        break;
                    case HttpMethod::POST:
                        $this->methodPost($routePath, $action);

                        break;
                    default:
                        throw new Exception("Problem while preparing routes!");
                }
            }
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }
}