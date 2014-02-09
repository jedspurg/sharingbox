<?php
defined('C5_EXECUTE') or die("Access Denied.");

$statext         = $_POST['statext'];
$sw              = $_POST['sw'];
$statlinkcomment = $_POST['statlinkcomment'];
$sbUID           = $_POST['sbUID'];
$controller      = new SharingboxBlockController();

$controller->link_share($statext, $statlinkcomment, $sw, $sbUID);
