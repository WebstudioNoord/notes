<?php
		
$app->get('/shop', function () use ($app) {
	//$app->flashNow('danger home msg1', 'Your credit card is expired');
	$app->flashNow('danger home msg2', 'Shop');
	$app->render('home.php');
});
