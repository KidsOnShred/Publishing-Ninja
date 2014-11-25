<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/inc/dbConnect.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/publisher/classes/Magazine.class.php');
	
	$Magazine = new Magazine($core, $_POST['magazineId']);

	$pageNum = $_POST['pageNum'];
	//$pageNum++; //Account for +1 offset of array - might fix this later

	if ($_POST['action'] == 'loadPage')
		$Magazine->createPage($pageNum);
	if ($_POST['action'] == 'getPageTitle')
		echo $Magazine->getPageTitle($pageNum);
	if ($_POST['action'] == 'getNextNavigation')
		echo $Magazine->getNextNavigation($pageNum);
	if ($_POST['action'] == 'getPreviousNavigation')
		echo $Magazine->getPreviousNavigation($pageNum)

?>
<script>
	$('.focuspoint').focusPoint();
</script>