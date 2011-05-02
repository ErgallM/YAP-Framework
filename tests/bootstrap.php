<?php
// Define path to application directory
defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(LIBRARY_PATH),
    get_include_path(),
)));

require_once("Yap/Loader.php");

use \Yap;

\Yap\Loader::initAutoloader();