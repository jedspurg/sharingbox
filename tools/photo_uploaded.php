<?php
defined('C5_EXECUTE') or die("Access Denied.");

session_start();

$u          = new User();
$numFiles   = $_SESSION['numFiles'];
$controller = new SharingboxBlockController();

$controller->photo_share($u->getUserID(), $numFiles, 2);


