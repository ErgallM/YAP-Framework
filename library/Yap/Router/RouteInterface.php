<?php
namespace Yap\Router;

interface RouteInterface
{
    /**
     * Проверяет путь на соответствие роутеру
     *
     * @abstract
     * @param string $path
     * @return false|array
     */
    public function match($path);

    /**
     * Get route name
     * 
     * @abstract
     * @return string
     */
    public function getName();

    /**
     * Set route name
     *
     * @abstract
     * @param string $name
     * @return RouteInterface
     */
    public function setName($name);

    /**
     * Set default variable
     * @param array $defaults
     */
    public function setDefaults(array $defaults);

    /**
     * Get default variable
     * @abstract
     * @return array
     */
    public function getDefaults();

    public function __invoke($path);
}
