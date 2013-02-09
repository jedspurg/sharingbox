<?php  
defined('C5_EXECUTE') or die("Access Denied.");

$offset = $_GET['offset'];
$sbUID = $_GET['sbUID'];
$controller = new SharingboxBlockController();
$controller->loadMorePosts($offset, $sbUID);
