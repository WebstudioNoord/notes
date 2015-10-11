<?php
require '../vendor/autoload.php';
session_cache_limiter(false);
session_start();

$app = new \Slim\Slim();

$app->container->singleton('db', function (){
	return new PDO("mysql:host=$_SERVER[APP_MYSQL_DB_HOST];dbname=$_SERVER[APP_MYSQL_DB_NAME]", $_SERVER[APP_MYSQL_DB_USERNAME], $_SERVER[APP_MYSQL_DB_PASSWORD]);
});

$app->get('/', function () use ($app) {
	$app->render('header.php');
    $app->render('home.php');
    $app->render('footer.php');
});

$app->get('/hello/:name', function ($name) use ($app) {
	//$app->redirect('/');	
	echo " Hello, $name";
});

$app->get('/logout', function () use ($app) {
	// Unset all of the session variables.
	$_SESSION = array();
	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}
	
	// Finally, destroy the session.
	session_destroy();
	
	$app->redirect('/');	
});

$app->post('/login', function () use ($app) {
    //Create book
    $paramValue = $app->request->post('username');
    //echo "$app->request->post('paramName')";
    $_SESSION[loggedIn]=$paramValue;
    $app->redirect('/');
});

$app->run();
