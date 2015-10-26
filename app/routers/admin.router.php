<?php

function admin() {
	if (!$_SESSION[LoggedIn]) {
		$app = \Slim\Slim::getInstance();
		$app->notFound();
		$app->stop();
	}
	if ($_SESSION[Username]!=='admin') {
		$app = \Slim\Slim::getInstance();
		$app->notFound();
		$app->stop();
	}
}

$app->map('/admin/php', 'admin', function () use ($app) {
	$username = $app->request->post('php');
	$app->render('admin/php.php',['php'=>$php]);
})->via('GET', 'POST');

$app->get('/admin/users', 'admin', function () use ($app) {
    //$sql = 'SELECT * FROM users WHERE name="'.$_SESSION[loggedIn].'"';
    $sql = 'SELECT * FROM users WHERE id>1';
    $user=$app->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    $app->render('admin/header.php');
    $app->render('partial/showall.php',['user'=>$user]);
    $app->render('partial/home.php',['user'=>$user]);
    $app->render('footer.php',['user'=>$user]);
    //$app->render('admin/showall.php',['user'=>$user]);
});
