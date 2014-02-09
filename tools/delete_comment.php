<?php
defined('C5_EXECUTE') or die("Access Denied.");

$commID = $_POST['commID'];
if($commID){
  $controller = new SharingboxBlockController();
  $controller->deleteComment($commID);
}
