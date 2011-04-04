<?php
namespace Yap\View\Helper;

abstract class Abs
{
    public $view = null;

    public function setView(Yap\View $view)
    {
        $this->view = $view;
        return $this;
    }

    public function direct()
    {
    }

    public function toString()
    {
        return '';
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function __construct($view = null)
    {
        if (null !== $view && $view instanceof \Yap\View\Helper\Abs) {
            $this->setView($view);
        }
    }
}
