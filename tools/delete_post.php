<?php  
defined('C5_EXECUTE') or die("Access Denied.");

	$pID = $_GET['pID'];
	$valt = $_GET['valt'];	
	$controller = new SharingboxBlockController();
	$controller->delete_post($pID, $valt);

