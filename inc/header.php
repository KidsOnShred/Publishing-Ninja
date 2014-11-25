<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<title><?php echo 'Publishing Ninja';//echo $PubManager->getName(); ?></title>

<!-- Bootstrap core CSS -->
<link href="/publisher/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/publisher/css/font-awesome.min.css">
<link rel="icon" href="/publisher/img/icon-ninja.png" type="image/x-icon" />

<!-- Custom styles for this template -->
<link href="/publisher/css/main.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/layouts.css" />
<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/sections.css" />
<link rel="stylesheet" type="text/css" href="/publisher/css/dropzone.css" />
<link rel="stylesheet" type="text/css" href="/publisher/css/jquery.Jcrop.css" />
<link rel="stylesheet" type="text/css" href="/publisher/css/spectrum.css" />
<link href="/publisher/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<script src="/publisher/js/modernizr.custom.17475.js"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><?php echo $PubManager->getName(); ?></a>
		</div>
		<div class="navbar-collapse collapse">
			<div class="navbar-publications navbar-right">
                <?php $PubManager->displayDropdownMenu(); ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?php echo $User->getFullname(); ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="/publisher/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
		</div>
	</div>
</div>

<div class="container-fluid">
    <div class="row">
    	<div class="col-sm-3 col-md-2 sidebar">
        	<?php $PubManager->displaySideMenu(); ?>
        </div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">