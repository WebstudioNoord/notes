<?php

$app->get('/', function () use ($app) {
	//$app->flashNow('danger home msg1', 'Your credit card is expired');
	//$app->flashNow('danger home msg2', '<a href="#" class="alert-link">...</a>');
	$app->render('home.php');
});
