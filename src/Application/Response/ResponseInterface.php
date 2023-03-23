<?php

namespace MVC\Application\Response;

interface ResponseInterface
{
    public function prepare(): array;
}