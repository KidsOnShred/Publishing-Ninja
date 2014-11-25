<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/dbConnect.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Magazine.class.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Publication.class.php');
	
	$magazineId = $_GET['magazineId'];
	$Magazine = new Magazine($core, $magazineId);
	
	$publicationId = $Magazine->getPublicationId();
	$Pub = new Publication($core, $publicationId);

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title><?php echo $Pub->getName(); ?> Magazine</title>
		<meta name="description" content="Bookblock: A Content Flip Plugin - Demo 4" />
		<meta name="keywords" content="javascript, jquery, plugin, css3, flip, page, 3d, booklet, book, perspective" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
        <link href="/publisher/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="/publisher/css/font-awesome.min.css">
        <link href="/publisher/css/main.css" rel="stylesheet">

		<link href="/publisher/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/default.css" />
		<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/bookblock.css" />
		<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/focuspoint.css" />
		<!-- custom demo style -->
		<link rel="stylesheet" type="text/css" href="/publisher/magazine/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/publisher/magazine/css/layouts.css" />
        <link rel="stylesheet" type="text/css" href="/publisher/magazine/css/sections.css" />
        <link rel="stylesheet" type="text/css" href="/publisher/magazine/css/publications/<?php echo $Pub->getId(); ?>.css" />
        
		<script src="/publisher/magazine/js/modernizr.custom.js"></script>
	</head>
	<body>
        <div class="container">
			<div class="bb-custom-wrapper">
            	<div id="bb-bookblock" class="bb-bookblock">
            		<div class="bb-item" style="display:block">
            			<div class="content" style="margin-top:0 !important">
							<?php
								$pageNum = $_GET['pageNum'];
                                echo '<input type="hidden" id="pageId" value="'.$Magazine->getIdFromPageNum($pageNum).'" />';
								echo '<input type="hidden" id="magazineId" value="'.$Magazine->getId().'" />';
								echo $Magazine->createPage($pageNum);
                            ?>	
        				</div>
            		</div>
            	</div>
            </div>
        </div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="/publisher/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script src="/publisher/magazine/js/jquerypp.custom.js"></script>
		<script src="/publisher/magazine/js/jquery.bookblock.js"></script>
		<script src="/publisher/magazine/js/jquery.focuspoint.js"></script>
        <script src="/publisher/js/html2canvas.js"></script>
        <script src="/publisher/magazine/js/template-preview.js"></script>
        <script>
			$('.focuspoint').focusPoint();
		</script>
	</body>
</html>