<?php

session_start();

require '../vendor/autoload.php';
require 'myview.php';

// https://helgesverre.com/blog/i18n-slim-framework-translation-twig/
// Use the ridiculously long Symfony namespaces
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

$app = new \Slim\Slim([
    'view' => new CustomView(),
    'templates.path' => '../app/templates'
]);

$app->container->singleton('db', function (){
	return new PDO("mysql:host=$_SERVER[APP_MYSQL_DB_HOST];dbname=$_SERVER[APP_MYSQL_DB_NAME]", $_SERVER[APP_MYSQL_DB_USERNAME], $_SERVER[APP_MYSQL_DB_PASSWORD]);
});

$app->container->set("translator", new Translator("nl_NL", new MessageSelector()));
$app->translator->setFallbackLocales(['en_US']);
// Add a loader that will get the php files we are going to store our translations in
$app->translator->addLoader('php', new PhpFileLoader());

// Automatically load router files
$languages = glob('../app/language/*');
foreach ((array)$languages as $language) {
	$langs = glob($language.'/*.php');
	foreach ((array)$langs as $lang) {
		$langNameArray = explode("/", $language);
		$langname = array_pop($langNameArray);
		$app->translator->addResource('php', $lang, $langname);
	}
}

// Automatically load router files
$routers = glob('../app/routers/*.router.php');
foreach ((array)$routers as $router) {
    require $router;
}

$app->run();