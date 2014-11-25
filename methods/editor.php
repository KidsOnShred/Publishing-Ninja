<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/dbConnect.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Editor.class.php');
	
	$Editor = new Editor($core);

	$Editor->init();
		
?>