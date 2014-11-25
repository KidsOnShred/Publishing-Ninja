<?php
	 // Start a PHP session
    session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/magazine/classes/Core.class.php');

	// Set the error reporting level
    error_reporting(E_ALL);
    ini_set("display_errors", 1);   
 
	// Create MySQL Connection using Core class
	$core = Core::getInstance();    
	
?>