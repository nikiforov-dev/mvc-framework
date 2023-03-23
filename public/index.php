<?php

$dirname = dirname(__DIR__);

require  $dirname . '/vendor/autoload.php';

use MVC\Application\Application;
use MVC\Application\Reader\JsonReader;
use MVC\Application\Service\ServiceLocator;

try {
    $services = (new JsonReader($dirname . '/config/services.json'))->read()['services'];

    ServiceLocator::setServicesFromArray($services);
    $routesMap = (new JsonReader($dirname . '/config/routes.json'))->read()['routes'];

    $app = new Application($routesMap);

    $app->run();
} catch (Throwable $e) {
    echo $e->getMessage();
}
