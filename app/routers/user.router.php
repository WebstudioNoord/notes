<?php

function user() {
	 if (!$_SESSION[LoggedIn]) {
		$app = \Slim\Slim::getInstance();
		$app->flashNow('danger', 'Login required');
		//$app->redirect('/');
		$app->render('user/blank.php');
		$app->stop();
     }
}

$app->post('/login', function () use ($app) {

    $username = $app->request->post('username');
    $password = $app->request->post('password');
    
    $_SESSION[Username]=$username;
    
    $gump = new GUMP();

	$_POST = $gump->sanitize($app->request->post()); // You don't have to sanitize, but it's safest to do so.
	
	$gump->validation_rules(array(
	    'username'    => 'required',
	    'password'    => 'required'
	));
	
	$gump->filter_rules(array(
	    'username' => 'trim|sanitize_string',
	    'password' => 'trim'
	));
	
	$validated_data = $gump->run($app->request->post());
	
	if($validated_data === false) {
		foreach($gump->get_readable_errors(false) as $k=>$v) {
			$app->flash('danger validate_'.$k, print_r($v,true));
		}
	} else {
	    //$app->flash('success validate', print_r($validated_data,true));

	    #http://stackoverflow.com/questions/4364686/how-do-i-sanitize-input-with-pdo
	    #https://youtu.be/sRfYgco3xo4?t=1758
	    $sql = 'SELECT * FROM users WHERE name=:name OR email=:name';
	    $user=$app->db->prepare($sql);
	    
	    /*** bind the paramaters ***/
	    $user->bindParam(':name', $username, PDO::PARAM_STR);
	    
	    /*** execute the prepared statement ***/
	    $user->execute();
	    
	    $user=$user->fetch();
	    
	    // store $hash in db at signup
	    //$hash = password_hash($password, PASSWORD_DEFAULT);
	    //echo $hash;
	    //die();
	    
	    if (password_verify($password, $user['password'])) {
			$_SESSION[Username]=$user['name'];
			$_SESSION[LoggedIn]=$username;
	    	$app->flash('success login', 'You are logged in.');
	    } else {
			if ($username || $password) $app->flash('danger login', 'Wrong Username and/or Password.');
		}
	}
	
	if ($app->request()->getHost()===str_replace("www.","",parse_url($app->request()->getReferrer(), PHP_URL_HOST))) {
		$app->redirect($app->request()->getReferrer());
	} else {
		$app->redirect('/');
	}
	
	/*
    //$app->redirect('/');
    $app->redirect($app->request->post('returnurl'));
    */
});

$app->get('/logout', function () use ($app) {
	// Unset all of the session variables.
	$_SESSION = array();
/*
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
*/

	$app->flash('success logout', $app->translator->trans('text.logged_out'));
	
	$app->redirect('/');	
});

$app->get('/signup', function () use ($app) {
	$app->render('login/signup.php');
});

