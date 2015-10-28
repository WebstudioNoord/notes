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

$app->map('/signup', function () use ($app) {
	
	if ($app->request->post()) {
		
		$post['username'] = $app->request->post('username');
		$post['email'] = $app->request->post('email');
		
		# https://github.com/Wixel/GUMP
	    $gump = new GUMP();
	    
	    GUMP::add_validator('istaken', function($field, $input, $param = NULL) {

			if (!empty($input[$field])) {
				
				$app = \Slim\Slim::getInstance();
				
			    # http://stackoverflow.com/questions/4364686/how-do-i-sanitize-input-with-pdo
			    # https://youtu.be/sRfYgco3xo4?t=1758
			    $sql = 'SELECT name FROM users WHERE name=:name OR email=:name';
			    $user=$app->db->prepare($sql);
	
			    /*** bind the paramaters ***/
			    $user->bindParam(':name', $input[$field], PDO::PARAM_STR);
			    
			    /*** execute the prepared statement ***/
			    $user->execute();
			    
			    $user=$user->fetch(PDO::FETCH_ASSOC);
			    
			    if (is_array($user)) {
			    	return false;
			    }
		    }
	    });
		
		$validation_rules_1=array(
		    'username'         => 'required|min_len,6|alpha_space|istaken',
		    'email'            => 'required|valid_email|istaken',
		    'password'         => 'required',
		    'password_confirm' => 'required'
		);
		
		$validation_rules_2=array(
		    'username'         => 'required|min_len,6|alpha_space',
		    'email'            => 'required|valid_email',
		    'password'         => 'required',
		    'password_confirm' => 'required'
		);
		
		$gump->validation_rules($validation_rules_1);
		
		$filter_array=array(
		    'username' => 'trim|sanitize_string|rmpunctuation',
		    'email' => 'trim|sanitize_string|sanitize_email',
		    'password' => 'trim',
		    'password_confirm' => 'trim'
		);
		$gump->filter_rules($filter_array);
		
		$validated_data = $gump->run($app->request->post());
		if ($validated_data !== false) {
			//
			if ($app->request->post('password')!==$app->request->post('password_confirm')) {
				$validated_data=false;
			}
		}
/*
if (is_array($validated_data)) {
	foreach($validated_data as $key => $val)
	{
	    $validated_data[$key] = htmlentities($val);
	}
}
echo '<pre>';var_dump($validated_data);echo '</pre>';
*/
		if ($validated_data === false) {
			$errors=$gump->validate($app->request->post(),$validation_rules_2);
			if (!is_array($errors)) {$errors=[];}
			$validate_username=GUMP::is_valid(['username' => $app->request->post('username')], ['username' => 'istaken']);
			if ($validate_username!==true) {
				$errors[]=array(
       		 		'field' => 'username',
        			'value' => '',
        			'rule' => 'validate_istaken',
        			'param' => '',
    			);
			}
			$validate_email=GUMP::is_valid(['email' => $app->request->post('email')], ['email' => 'istaken']);
			if ($validate_email!==true) {
				$errors[]=array(
       		 		'field' => 'email',
        			'value' => '',
        			'rule' => 'validate_istaken',
        			'param' => '',
    			);
			}
			if ($app->request->post('password')!==$app->request->post('password_confirm')) {
				$errors[]=array(
       		 		'field' => 'password_confirm',
        			'value' => '',
        			'rule' => 'validate_password_confirm',
        			'param' => '',
    			);
			}
			if (is_array($errors)) {
				foreach($errors as $k=>$v) {
					$transfield=$app->translator->trans('user.signup.form.'.$v['field']);
					$transerrors[$v['field']][]=$app->translator->trans('user.error.'.$v['rule'].' %field% %param%',['%field%' => $transfield,'%param%' => $v['param']]);
				}
			}
			
			$app->render('login/signup.php',['errors'=>$errors,'transerrors'=>$transerrors,'post'=>$post]);

		} else {
			
			# TODO try catch loop
			# http://stackoverflow.com/questions/18655706/pdo-with-insert-into-through-prepared-statements
		    $sql = 'INSERT INTO users(name, email, password) VALUES(:name, :email, :password)';
		    $user=$app->db->prepare($sql);
		    
		    /*** bind the paramaters ***/
		    $user->bindParam(':name', $validated_data['username'], PDO::PARAM_STR);
		    $user->bindParam(':email', $validated_data['email'], PDO::PARAM_STR);
		    
		    $hash = password_hash($validated_data['password'], PASSWORD_DEFAULT);
		    $user->bindParam(':password', $hash, PDO::PARAM_STR);
		    
		    /*** execute the prepared statement ***/
		    $user->execute();
		    
		    # http://stackoverflow.com/questions/6728361/php-e-mail-form-sender-name-instead-of-e-mail
		    // create email headers
		    $headers = 'From: Webstudio Noord<info@webstudionoord.nl>'."\r\n".
		    'X-Mailer: PHP/' . phpversion();
		    //@mail($email_to, $email_subject, $email_message, $headers); 
		    mail($validated_data['email'],"Hello World","Email sent using PHP via msmtp",$headers);
		    
		    /*
		    if (password_verify($password, $user['password'])) {
				$_SESSION[Username]=$user['name'];
				$_SESSION[LoggedIn]=$username;
		    	$app->flash('success login', 'You are logged in.');
		    } else {
				if ($username || $password) $app->flash('danger login', 'Wrong Username and/or Password.');
			}
			*/
			
			$app->flashNow('success signup', 'succesfull signup');
			$app->render('login/signup.php',['post'=>$post]);
			//$app->flash('success signup', 'succesfull signup');
			//$app->redirect('/');
		}
	}
	else {
		$app->render('login/signup.php');
	}
	
})->via('GET', 'POST');;

