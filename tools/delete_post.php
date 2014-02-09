<?php
defined('C5_EXECUTE') or die("Access Denied.");

$pID = $_POST['pID'];
if($pID){
	$controller = new SharingboxBlockController();
	$controller->delete_post($pID);
}

