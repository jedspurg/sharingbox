<?php  
defined('C5_EXECUTE') or die("Access Denied.");

	$pID = $_GET['pID'];
	$blockArea = $_GET['blockArea'];
	$cID = $_GET['cID'];
	
	
	$controller = new CwsShareBlockController();
	$controller->delete_post($pID, $blockArea, $cID);



?>
