<?php
	
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/init.php');



	
	$error = false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if ($_POST['email'] != '' && $_POST['password'] != '')
		{
			//Stop brute force attacks
			if (!isset($_SESSION['attempt']))
				$_SESSION['attempt'] = 1;
			else
				$_SESSION['attempt']++;
			if (isset($_POST['remember']))
				$remember = true;
			else
				$remember = false;
			
			//Check email/password
			if ($User->logIn($_POST['email'], $_POST['password'], $remember))
				header("Location: /publisher/index.php");
			else
				$error = true;
			
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<title><?php echo 'Publishing Ninja'; ?></title>

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
<link href="/publisher/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<script src="/publisher/js/modernizr.custom.17475.js"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="background">
	<div class="container-fluid">
		<div class="row">
	  		<div class="col-md-3 col-md-offset-4">
				<?php
				if ($error)
					echo '<h2 class="error">Incorrect username or password</h2>';
				echo $PubManager->getLogo();
				?>
		    	<form role="form" action="/publisher/login.php" method="post">
			  		<div class="form-group">
			    		<label for="inputEmail3" class="control-label">Email</label>
			    		<input class="form-control" type="email" name="email" placeholder="Email" />
			  		</div>
					<div class="form-group">
					    <label for="inputPassword3" class="control-label">Password</label>
					    <input type="password" class="form-control" name="password" placeholder="Password" />
					</div>
					<div class="form-group">
					    <div class="checkbox">
					        <label>
					        	<input type="checkbox" name="remember" id="remember" value="1" checked /> Remember me
					        </label>
					    </div>
					</div>
					<div class="form-group">
				    	<?php
							/*if (@$_SESSION['attempt'] > 4)
			                    echo '<a class="btn btn-danger">You have tried to log in too many times. Please wait.</a>';*/
			               // else
			                    echo '<button type="submit" class="btn btn-default">Sign in</button>';
			            ?>
					      
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/footer.php');
?>
