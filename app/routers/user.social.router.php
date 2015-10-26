<?php

$app->get('/profile', 'user', function () use ($app) {
	    $app->render('user/profile/edit.php');
});

$app->get('/profile/:name', function ($name) use ($app) {
	//$app->redirect('/');	
	//echo " Hello, $name";
	$app->render('user/profile/show.php',['user'=>$name]);
});
