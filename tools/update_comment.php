<?php  
defined('C5_EXECUTE') or die("Access Denied.");


if($_GET['pID']) {
	
	$pID = $_GET['pID'];
	$commID = $_GET['commID'];
	$comtext = $_GET['comtext'];
	
	$controller = new CwsCommentsBlockController();
	$controller->update_comment($pID, $commID, strip_tags($comtext));
}


?>