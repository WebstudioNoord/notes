<?php
require '../vendor/autoload.php';
require 'myview.php';
session_cache_limiter(false);
session_start();

$app = new \Slim\Slim(array(
    'view' => new CustomView()
));

$app->container->singleton('db', function (){
	return new PDO("mysql:host=$_SERVER[APP_MYSQL_DB_HOST];dbname=$_SERVER[APP_MYSQL_DB_NAME]", $_SERVER[APP_MYSQL_DB_USERNAME], $_SERVER[APP_MYSQL_DB_PASSWORD]);
});

require 'routes.php';

$app->run();
