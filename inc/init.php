<?php 	
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/dbConnect.php');
	
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/PublicationManager.class.php');

    include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/User.class.php');
	
	$PubManager = new PublicationManager($core);

    $User = $PubManager->getUser();

    $User->auth();

?>