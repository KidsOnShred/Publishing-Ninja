<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/dbConnect.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Editor.class.php');
	
	$Editor = new Editor($core);
	
	$img = $_POST['img'];
	$magazineId = $_POST['magazineId'];
	$pageId = $_POST['pageId'];

	$file = '/home/ninja/public_html/publisher/content/img/thumbnails/thumb-'.$magazineId.'-'.$pageId.'.png';
	
	$uri =  substr($img,strpos($img,",")+1);
	
	file_put_contents($file, base64_decode($uri));
	
	$thumb = $Editor->createThumbnail($file, 140, 120);
	
	move_uploaded_file($thumb, $file);

	echo '<img src="/publisher/content/img/thumbnails/thumb-'.$magazineId.'-'.$pageId.'.png" />';
?>