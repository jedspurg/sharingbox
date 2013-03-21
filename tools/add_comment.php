<?php  
defined('C5_EXECUTE') or die("Access Denied.");

if($_GET['pID']) {
	$pID = $_GET['pID'];
	$comtext = $_GET['comtext'];
	$valt = $_GET['valt'];
	$controller = new SharingboxBlockController();
	$controller->add_comment($pID, strip_tags($comtext), $valt);
}
