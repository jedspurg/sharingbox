<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$u = new User();
$c = Page::getCurrentPage();
if($u->isRegistered() && $visibility > 0){?>
<div id="cws-share-wrapper" class="wall-share-wrap">
    <div class="well">
        <div id="share-bar">
            <div id="cws-share"><strong><?php  echo t('Share:')?></strong></div>
            <div id="cws-status"><a><i class="icon-hand-right"></i> <?php  echo t('Status')?></a></div>
            <div id="cws-link"><a><i class="icon-share"></i> <?php  echo t('Link')?></a></div>
           <?php  
           $gallerybox = Loader::package('gallerybox'); 
           if (is_object($gallerybox)) {?>
               <div id="cws-photo"><a><i class="icon-camera"></i> <?php  echo t('Photo')?></a></div>
           <?php  }?>
        </div>
        <div class="clearfix"></div>
        <div id="status-field"> 
            <form id="cws-status-form" method="post" action="">
            <input name="uID" type="hidden" value="<?php  echo $u->getUserID()?>" id="uID" />
            <input name="action" type="hidden" value="" id="action" />
            <input name="sw" type="hidden" value="" id="sw" />
   
            
            <div id="statlinkcomment-wrap" class="input-prepend">
            <span class="add-on"><i class="icon-comment"></i></span>
            <input type="text" class="span6" name="statlinkcomment" id="statlinkcomment" />
            </div>
         	<div class="input-prepend input-append">
  			<span class="add-on"><a id="cws-everyone" title="<?php  echo t('Everyone')?>" data-placement="left"><i class="icon icon-globe">&nbsp;</i></a><a id="cws-friends" title="<?php  echo t('Friends')?>" data-placement="left" ><i class="icon icon-user">&nbsp;</i></a></span>
            <input type="text" class="span9" name="statext" id="statext" />
 
            <button type="submit" name="submit" class="btn btn-primary pull-right" id="status-post"><?php  echo t('Share')?></button>
            
			</div>
            
            </form>
        </div>
        
         <div class="modal hide ccm-ui" id="cwsPhotoUploadModal" role="dialog" aria-labelledby="cwsPhotoUpload" aria-hidden="true" data-backdrop="true">
        	<div class="modal-header">
        		<h3 id="cwsPhotoUpload"><?php echo t('Upload Images')?></h3>
        	</div>
	
          <div class="modal-body">
          <form id="cws-photo-upload-form" action="<?php  echo DISPATCHER_FILENAME?>" method="post" enctype="multipart/form-data">
              <table border="0" width="100%" cellspacing="0" cellpadding="0" id="ccm-file-upload-multiple-list">
                  <tr>
                      <th colspan="2">
                          <div style="width: 80px; float: right">
                              <span id="ccm-file-upload-multiple-spanButtonPlaceHolder"></span>
                          </div>
                          <?php  echo t('Upload Queue');?>
                      </th>
                  </tr>
              </table>
          </div>
          <div class="modal-footer">
            <button id="photo-upload-btn" class="btn btn-primary" onclick="swfu.startUpload();return false;" data-loading-text="Uploading..." autocomplete="off"><?php   echo t('Start upload')?></button>
            <button class="btn pull-left"  data-dismiss="modal" aria-hidden="true" onclick="swfu.cancelQueue();" id="ccm-file-upload-multiple-btnCancel"><?php   echo t('Cancel')?></button>
            </form>
        </div>
       </div>
       
    </div>  
    
<?php  
if (is_object($gallerybox) && !$c->isEditMode()) {
	Loader::packageElement('sb_gb_uploader','sharingbox', array('searchInstance' => $searchInstance));
}
?>            
</div>
<?php }?>

<?php 
if(is_array($postings) && !$c->isEditMode()){
	Loader::packageElement('sb_postings','sharingbox', array('postings'=>$postings));
}
?>

<div class="wall-share-wrap">
  <div id="more-posts"></div>
  <div id="more-posts-loader" class="loading"></div>
</div>
  








