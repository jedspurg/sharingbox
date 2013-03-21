<?php  
defined('C5_EXECUTE') or die("Access Denied.");

	$statext = $_GET['statext'];
	$sw = $_GET['sw'];
	$statlinkcomment = $_GET['statlinkcomment'];
	$sbUID = $_GET['sbUID'];
	$controller = new SharingboxBlockController();
	$controller->link_share($statext, $statlinkcomment, $sw, $sbUID);
