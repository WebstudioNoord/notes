<?php

$app->get('/', function () use ($app) {
	    //$app->flashNow('foo', 'Your credit card is expired');
	    $app->render('home.php');
});

$app->get('/user', function () use ($app) {
    $sql = 'SELECT * FROM users WHERE name="'.$_SESSION[loggedIn].'"';
    $user=$app->db->query($sql)->fetch();
    echo "<pre>";
    var_dump($user);    
});

$app->get('/hello/:name', function ($name) use ($app) {
	//$app->redirect('/');	
	echo " Hello, $name";
});

$app->post('/login', function () use ($app) {
    //Create book
    $username = $app->request->post('username');
    $password = $app->request->post('password');
    //echo "$app->request->post('paramName')";
    
    #http://stackoverflow.com/questions/4364686/how-do-i-sanitize-input-with-pdo
    
    $sql = 'SELECT * FROM users WHERE name="'.$username.'" and password="'.$password.'"';
    $user=$app->db->query($sql)->fetch();
    if ($user) {$_SESSION[loggedIn]=$username;}
    $app->flash('foo', 'You are logged in.');
    $app->redirect('/');
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
