<?php
namespace Yap\Filter;

class UrlSlash extends FilterAbstract
{
    /**
     * Обрезание лишних слешей в url по бокам
     *
     * @param array|string $value
     * @return array|string
     */
    public function filter($value)
    {
        $filter = function($value) {
            if ('/' == substr($value, 0, 1)) $value = substr($value, 1);
            if ('/' == substr($value, strlen($value) - 1)) $value = substr($value, 0, -1);
            return $value;
        };

        if (is_array($value)) {
            foreach ($value as &$val) {
                $val = $filter($val);
            }
        } else {
            $value = $filter($value);
        }

        return $value;
    }
}