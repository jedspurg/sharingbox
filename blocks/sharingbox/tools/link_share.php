<?php  
defined('C5_EXECUTE') or die("Access Denied.");




	$statext = $_GET['statext'];
	$sw = $_GET['sw'];
	$blockArea = $_GET['blockArea'];
	$cID = $_GET['cID'];
	$statlinkcomment = $_GET['statlinkcomment'];
	
	$controller = new CwsShareBlockController();
	$controller->link_share($statext, $statlinkcomment, $sw, $blockArea, $cID);



?>