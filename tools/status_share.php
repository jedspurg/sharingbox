<?php
defined('C5_EXECUTE') or die("Access Denied.");

$statext    = $_POST['statext'];
$sw         = $_POST['sw'];
$sbUID      = $_POST['sbUID'];
$controller = new SharingboxBlockController();

$controller->status_share($statext, $sw, $sbUID);
