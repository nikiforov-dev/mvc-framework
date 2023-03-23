<?php

namespace MVC\Controller;

use MVC\Application\Response\Response;
use MVC\Application\Base\Controller\AbstractController;
use MVC\Application\Request;

class AppController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return new Response("test: {$request->getMethod()} {$request->getPath()}");
    }
    public function index(Request $request): Response
    {
        return new Response('OK!', [], 200);
    }
}