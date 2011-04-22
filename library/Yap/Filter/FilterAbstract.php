<?php
namespace Yap\Filter;

abstract class FilterAbstract
{
    public function filter($value)
    {
        return $value;
    }

    public function __invoke($value)
    {
        return $this->filter($value);
    }
}
