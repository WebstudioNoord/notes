<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?= $_SERVER['SERVER_NAME'] ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<!-- http://stackoverflow.com/a/31836425 -->
<nav class="navbar navbar-default ">
</nav>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Project name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <!--
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../navbar/">Default</a></li>
            <li><a href="../navbar-static-top/">Static top</a></li>
            <li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li>
          </ul>
          -->
				<?php if ($_SESSION[LoggedIn]) { ?>
			      <ul class="nav navbar-nav navbar-right">
		            <li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $this->trans('text.welcome %name%', ['%name%' => $_SESSION[Username]]) ?> <span class="caret"></span></a>
		              <ul class="dropdown-menu col-xs-1">
						<li><a href="/profile" class="text-right">Profile</a></li>
		                <li><a href="/logout" class="text-right">Logout</a></li>
		              </ul>
		            </li>
          	      </ul>
				<?php } else { ?>
                <form class="navbar-form navbar-right" role="search" action="/login" method="post">

                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Username" value="<?= $_SESSION[Username] ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <input type="hidden" name="returnurl" value="<?= $_SERVER["REQUEST_URI"] ?>">
                    <button type="submit" class="btn btn-default">Sign In</button>
                    <a class="btn btn-success" href="/signup">Sign Up</a>
                </form>
                <?php } ?>
                
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
        <?php 
        foreach ($flash as $key => $value) { ?>
          <div class="alert alert-<?=$key?>" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?=$value?>
          </div>
        <?php } ?>
    </div>

    <div class="container">