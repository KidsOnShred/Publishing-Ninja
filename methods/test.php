<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/dbConnect.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Publication.class.php');
	
	$Publication = new Publication($core, $_POST['publicationId']);

	$Publication->init();
		
?>