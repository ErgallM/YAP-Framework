<?php
namespace Yap\View\Helper;
use Yap\View\HelperAbstract as HelperAbstract;

class HeadTitle extends HelperAbstract
{
    private $_headTitleKey = 'YapViewHelperHeadTitle';

    static private $_separator = ' / ';

    public function HeadTitle($title)
    {
        if (empty($title)) return $this;
        
        $stec = (\Yap\Registry::isRegistered($this->_headTitleKey))
                ? (array) \Yap\Registry::get($this->_headTitleKey)
                : array();

        foreach ((array) $title as $t) {
            $stec[] = $t;
        }

        \Yap\Registry::set($this->_headTitleKey, $stec);

        return $this;
    }

    public function toString()
    {
        $result = array();
        $stec = (\Yap\Registry::isRegistered($this->_headTitleKey))
                ? (array) \Yap\Registry::get($this->_headTitleKey)
                : array();

        while ($title = array_pop($stec)) {
            $result[] = $title;
        }
        
        return '<title>'
            . ((sizeof($result)) ? implode(self::$_separator, $result) : '')
        . '</title>' . PHP_EOL;
    }

    /**
     * Set headtitle separator
     * @param  $separator
     * @return HeadTitle
     */
    public function setSeparator($separator)
    {
        self::$_separator = $separator;
        return $this;
    }

    /**
     * Get headtitle separator
     * 
     * @return string
     */
    public function getSeparator()
    {
        return self::$_separator;
    }
}
