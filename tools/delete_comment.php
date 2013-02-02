<?php  
defined('C5_EXECUTE') or die("Access Denied.");

	
	$commID = $_GET['commID'];
	
	
	$controller = new CwsCommentsBlockController();
	$controller->delete_comment($commID);



?>
