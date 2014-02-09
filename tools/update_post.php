<?php
defined('C5_EXECUTE') or die("Access Denied.");

$pType           = $_POST['pType'];
$pID             = $_POST['pID'];
$statext         = $_POST['statext'];
$sw              = $_POST['sw'];
$statlinkcomment = $_POST['statlinkcomment'];
$sbUID           = $_POST['sbUID'];

$controller      = new SharingboxBlockController();

switch($pType){
	case 'sb_status':
	$controller->update_status_share($pID, $statext, $sw, $sbUID);
	break;
	case 'sb_link':
	$controller->update_link_share($pID, $statext, $statlinkcomment, $sw, $sbUID);
	break;
}
