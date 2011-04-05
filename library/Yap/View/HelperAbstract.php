<?php
namespace Yap\View;

abstract class HelperAbstract
{
    public $view = null;

    public function setView(Yap\View $view)
    {
        $this->view = $view;
        return $this;
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
