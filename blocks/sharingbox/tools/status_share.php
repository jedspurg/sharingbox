<?php  
defined('C5_EXECUTE') or die("Access Denied.");




	$statext = $_GET['statext'];
	$sw = $_GET['sw'];
	$blockArea = $_GET['blockArea'];
	$cID = $_GET['cID'];
	
	$controller = new CwsShareBlockController();
	$controller->status_share($statext, $sw, $blockArea, $cID);



?>