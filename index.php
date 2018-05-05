<?php
use App\App;

define('ROOT', (__DIR__));

spl_autoload_register(function ($class_name) {
     $file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name).'.php';
     $path = ROOT.DIRECTORY_SEPARATOR.$file;
        if (file_exists($path)) {
        	require_once $path;
        }else{
	     echo $path;die;
        }
});
$app = App::instance();
$app->run();
