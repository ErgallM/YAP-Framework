<?php
//Test debug run
/*$_SERVER['argv'][] = '--configuration';*/
$_SERVER['argv'][] = 'Yap';

chdir(dirname(__FILE__));
require_once("phpunit.php");