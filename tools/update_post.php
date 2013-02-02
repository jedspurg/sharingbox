<?php  
defined('C5_EXECUTE') or die("Access Denied.");

	$pType = $_GET['pType'];
	$pID = $_GET['pID'];
	$statext = $_GET['statext'];
	$sw = $_GET['sw'];
	$blockArea = $_GET['blockArea'];
	$cID = $_GET['cID'];
	$statlinkcomment = $_GET['statlinkcomment'];
	
	$controller = new CwsShareBlockController();
	switch($pType){
		
		case 'CWS-Status':
		$controller->update_status_share($pID, $statext, $sw, $blockArea, $cID);
		break;
		
		case 'CWS-Link':
		$controller->update_link_share($pID, $statext, $statlinkcomment, $sw, $blockArea, $cID);
		break;
		
	}
	
	



?>
