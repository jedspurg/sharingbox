<?php   
defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$uForm = Loader::helper('form/user_selector');
?>
<div class="ccm-block-field-group">
  <label for="visibility"><strong><?php echo t('Show only posts?')?></strong> <?php echo t('(Hide SharingBox)')?></label>
  <?php   print $form->radio('visibility','1', $visibility);echo t('No');?>
  <?php   print $form->radio('visibility','0', $visibility);echo t('Yes');?>
</div>

<div class="ccm-block-field-group">
	<label for="type"><strong><?php   echo t('Show Posts for:')?></strong></label>
	<?php   print $form->select('type', array('3'=>t('Everyone (global site postings)'), '1'=>t('Profile'), '2'=>t('Selected User')), $type);?>
</div>

<div class="ccm-block-field-group">
  <div id="user-selector2" <?php   if($type != '2'){?>style="display:none"<?php   }?>>
  <?php  
  print $uForm->selectUser('uID', $uID, $javascriptFunc = 'ccm_triggerSelectUser');
  ?>
  </div>
</div>



