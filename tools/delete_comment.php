<?php  
defined('C5_EXECUTE') or die("Access Denied.");

$commID = $_GET['commID'];
$controller = new SharingboxBlockController();
$controller->deleteComment($commID);
?>
