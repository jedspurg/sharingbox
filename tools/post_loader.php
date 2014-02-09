<?php
defined('C5_EXECUTE') or die("Access Denied.");

$offset     = $_POST['offset'];
$sbUID      = $_POST['sbUID'];
$controller = new SharingboxBlockController();

$controller->loadMorePosts($offset, $sbUID);
