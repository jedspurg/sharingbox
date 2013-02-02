<?php  
defined('C5_EXECUTE') or die("Access Denied.");
	session_start();
	$u = new User();
	$numFiles = $_SESSION['numFiles'];
	$controller = new CwsShareBlockController();
	$blockArea = $_GET['blockArea'];
	$cID = $_GET['cID'];

	$controller->photo_share($u->getUserID(), $numFiles, 2, $blockArea, $cID);

?>


