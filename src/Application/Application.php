<?php

namespace MVC\Application;

use MVC\Application\Response\Response;
use MVC\Application\Response\ResponseInterface;
use Throwable;

class Application
{
    protected Router $router;
    protected array $controllers = [];

    public function __construct(array $routes)
    {
        $this->router = new Router($routes);
    }

    public function run(): void
    {
        try {
            $response = $this->router->resolve();
        } catch (Throwable $e) {
            $response = new Response($e->getMessage(), [], 500);
        }

        $this->showResponse($response);
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    private function showResponse(ResponseInterface $response)
    {
        $responseData = $response->prepare();

        http_response_code($responseData['statusCode']);
        foreach ($responseData['headers'] as $header) {
            header($header);
        }

        echo $responseData['content'];
    }
}