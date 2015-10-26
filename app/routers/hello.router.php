<?php

$app->get('/hello/:name', function ($name) use ($app) {
	//$app->redirect('/');	
	echo " Hello, $name";
});
