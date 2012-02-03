<?php
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE);
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';
require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Spray.php';