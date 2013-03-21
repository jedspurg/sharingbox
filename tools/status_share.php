<?php  
defined('C5_EXECUTE') or die("Access Denied.");

	$statext = $_GET['statext'];
	$sw = $_GET['sw'];
	$sbUID = $_GET['sbUID'];
	$valt = $_GET['valt'];	
	$controller = new SharingboxBlockController();
	$controller->status_share($statext, $sw, $sbUID, $valt);
