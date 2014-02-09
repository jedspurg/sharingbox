<?php
defined('C5_EXECUTE') or die("Access Denied.");

$pID = $_POST['pID'];
if($pID){
	$comtext    = $_POST['comtext'];
	$valt       = $_POST['ccm_token'];
	$controller = new SharingboxBlockController();

	$controller->add_comment($pID, strip_tags($comtext), $valt);
}
