<?php
defined('C5_EXECUTE') or die("Access Denied.");

$pID = $_POST['pID'];
if($pID){
	$commID     = $_POST['commID'];
	$comtext    = $_POST['comtext'];
	$controller = new SharingboxBlockController();

	$controller->updateComment($pID, $commID, strip_tags($comtext));
}
