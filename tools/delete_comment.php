<?php  
defined('C5_EXECUTE') or die("Access Denied.");

$commID = $_GET['commID'];
$valt = $_GET['valt'];
$controller = new SharingboxBlockController();
$controller->deleteComment($commID, $valt);

