<?php  
defined('C5_EXECUTE') or die("Access Denied.");


$offset = $_GET['offset'];
$controller = new SharingboxBlockController();
$controller->loadMorePosts($offset);

?>