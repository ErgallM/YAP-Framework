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
}
