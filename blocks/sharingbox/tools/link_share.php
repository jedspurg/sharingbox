<?php  
defined('C5_EXECUTE') or die("Access Denied.");

	$statext = $_GET['statext'];
	$sw = $_GET['sw'];
	$statlinkcomment = $_GET['statlinkcomment'];
	
	$controller = new SharingboxBlockController();
	$controller->link_share($statext, $statlinkcomment, $sw);

?>