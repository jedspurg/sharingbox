<?php  
defined('C5_EXECUTE') or die("Access Denied.");

	$pType = $_GET['pType'];
	$pID = $_GET['pID'];
	$statext = $_GET['statext'];
	$sw = $_GET['sw'];
	$statlinkcomment = $_GET['statlinkcomment'];
	$sbUID = $_GET['sbUID'];
	$controller = new SharingboxBlockController();
	switch($pType){
		
		case 'sb_status':
		$controller->update_status_share($pID, $statext, $sw, $sbUID);
		break;
		
		case 'sb_link':
		$controller->update_link_share($pID, $statext, $statlinkcomment, $sw, $sbUID);
		break;
		
	}
