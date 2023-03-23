<?php

namespace MVC\Application\Service;

class ServiceLocator
{
    private static array $services = [];

    /**
     * @param array $services
     *
     * @return void
     */
    public static function setServicesFromArray(array $services): void
    {
        foreach ($services as $service) {
            self::addService($service['name']);
        }
    }

    /**
     * @param string $className
     * @return void
     */
    public static function addService(string $className): void
    {
        $object = new $className();

        self::$services[$className] = $object;
    }

    /**
     * @return array|null
     */
    public static function getService(string $name): object|null
    {
        return self::$services[$name] ?? null;
    }
}