<?php 
$c = Page::getCurrentPage();
$u = new User();?>
<script>
var blockArea = '<?php echo $this->block->getAreaHandle()?>';
var cID = '<?php echo $c->getCollectionID()?>';
</script>
<?php if($u->isRegistered()){?>
<div id="cws-share-wrapper" class="wall-share-wrap">
    <div class="well">
        <div id="share-bar">
            <div id="cws-share"><strong><?php  echo t('Share:')?></strong></div>
            <div id="cws-status"><a><i class="icon-hand-right"></i> <?php  echo t('Status')?></a></div>
            <div id="cws-link"><a><i class="icon-share"></i> <?php  echo t('Link')?></a></div>
           <?php  
           $gallerybox = Loader::package('gallerybox'); 
           if (is_object($gallerybox)) {?>
               <div id="cws-photo"><a data-toggle="modal" href="#cwsPhotoUploadModal"><i class="icon-camera"></i> <?php  echo t('Photo')?></a></div>
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
        
         <div class="modal hide" id="cwsPhotoUploadModal" role="dialog" aria-labelledby="cwsPhotoUpload" aria-hidden="true" data-backdrop="true">
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
if (is_object($gallerybox)) {
$ch = Loader::helper('concrete/file');
$h = Loader::helper('concrete/interface');
$form = Loader::helper('form');
$html = Loader::helper('html');



$types = array('jpg','png','gif','jpeg');
global $c;
$ocID = 1;
$types = $ch->serializeUploadFileExtensions($types);
$valt = Loader::helper('validation/token');

?>

<script type="text/javascript" src="<?php  echo ASSETS_URL_JAVASCRIPT?>/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php  echo ASSETS_URL_JAVASCRIPT?>/swfupload/swfupload.handlers.js"></script>
<script type="text/javascript" src="<?php  echo ASSETS_URL_JAVASCRIPT?>/swfupload/swfupload.fileprogress.js"></script>
<script type="text/javascript" src="<?php  echo ASSETS_URL_JAVASCRIPT?>/swfupload/swfupload.queue.js"></script>





<?php  
$umf = ini_get('upload_max_filesize');
$umf = str_ireplace(array('M', 'K', 'G'), array(' MB', 'KB', ' GB'), $umf);
?>

<script type="text/javascript">


var swfu;
$(function() { 
	swfu = new SWFUpload({

		flash_url : "<?php    echo ASSETS_URL_FLASH?>/swfupload/swfupload.swf",
		upload_url : "<?php    echo Loader::helper('concrete/urls')->getToolsURL('user_multiple', 'gallerybox')?>",
		post_params: {'ccm-session' : "<?php     echo session_id(); ?>",'searchInstance': '<?php    echo $searchInstance?>', 'ocID' : '<?php    echo $ocID?>', 'ccm_token' : '<?php    echo $valt->generate("upload")?>'},
		file_size_limit : "<?php    echo $umf?>",
		file_types : "<?php    echo $types?>",
		button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
		file_types_description : "All Files",
		file_upload_limit : 100,
		button_cursor: SWFUpload.CURSOR.HAND,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "ccm-file-upload-multiple-list",
			cancelButtonId : "ccm-file-upload-multiple-btnCancel"
		},
		debug: false,

		// Button settings
		button_image_url: "<?php    echo ASSETS_URL_IMAGES?>/icons/add_file_swfupload.png",	// Relative to the Flash file
		button_width: "80",
		button_text: '<span class="uploadButtonText"><?php    echo t('Add Files')?><\/span>',
		button_height: "16",
		button_text_left_padding: 18,
		button_text_style: ".uploadButtonText {background-color: #eee; font-family: Helvetica Neue, Helvetica, Arial}",
		button_placeholder_id: "ccm-file-upload-multiple-spanButtonPlaceHolder",
		
		// The event handler functions are defined in handlers.js
		// wrapped function with apply are so c5 can do anything special it needs to
		// some functions needed to be overridden completly
		file_queued_handler : function (file) {
			fileQueued.apply(this,[file]);
		},
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : function(numFilesSelected, numFilesQueued){
			try {
				if (numFilesSelected > 0) {					
					$("#ccm-file-upload-multiple-btnCancel").removeClass('disabled');
				}								
				//this.startUpload();
			} catch (ex)  {
				this.debug(ex);
			}		
		},
		upload_start_handler : uploadStart,
		upload_progress_handler : function(file, bytesLoaded, bytesTotal){
			try {
				var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				progress.setProgress(percent);
				
				progress.setStatus("Uploading... ("+percent+"%)");
			} catch (ex) {
				this.debug(ex);
			}		
		},
		upload_error_handler : uploadError,
		upload_success_handler : function(file, serverData){
			try {
				eval('serverData = '+serverData);
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				if (serverData['error'] == true) {
					progress.setError(serverData['message']);
				} else {
					progress.setComplete();		
				}
				progress.toggleCancel(false);
				if(serverData['id']){
					if(!this.highlight){this.highlight = [];}
					this.highlight.push(serverData['id']);
					if(ccm_uploadedFiles && serverData['id']!='undefined') ccm_uploadedFiles.push(serverData['id']);
					
				} 
				 
			} catch (ex) {
				this.debug(ex);
			}		
		},
		upload_complete_handler : '', 
		queue_complete_handler : function(file){
			// queueComplete() from swfupload.handlers.js
			//console.log(ccm_uploadedFiles.length);
			if (ccm_uploadedFiles.length > 0) {
				queueComplete();
				cws_filesUploadedDialog('<?php    echo $searchInstance?>'); 
				cwsCloseModal();
					
			}
		}
	});

	
});
</script>
	

<?php }?>

            
</div>
<?php }?>

<?php   

    $u = new User();
	$bt = BlockType::getByHandle('cws_comments');
    $wall = Loader::helper('wall', 'lerteco_wall');
    $av = Loader::helper('concrete/avatar');
    $date = Loader::helper('date');
	Loader::model('users_friends');
	$uh = Loader::helper('concrete/urls');

	

?>
<div id="commentable_lerteco_wall" class="wall-share-wrap">
<div class="loading"></div>
<ul class="lerteco_wall">
    <?php  
	
	foreach ($postings as $posting) {
		
		
        $user = $posting->getUserInfo();
        $profile_url = $this->url('/profile','view', $user->getUserID());
		$friendsData = UsersFriends::getUsersFriendsData($user->getUserID());
		//set a default share if none is recorded
		$data = unserialize($posting->pData);
		$sw = $data[1];
		if($sw == '1'){
			for($i = 0;$i < count($friendsData);$i++){
				if($friendsData[$i]['friendUID'] == $u->getUserID()){
					$cws_friend = true;
					break;
				}
			}
		}
				
		if($cws_friend || $sw == '2' || $u->getUserID() == $user->getUserID() || $u->isSuperUser()){
    ?>
        <li id ="cws-item-class_<?php echo $posting->pID?>" class="lerteco-wall-item">
        	<div class="wall-user-img">
            <a href="<?php   echo $profile_url ?>"><?php   echo $av->outputUserAvatar($user, false, 0.5) ?></a>
            <div class="user-arrow-border"></div>
            <div class="user-arrow"></div>
            </div>
            <div class="commentable-wall-item-container">
                <div class="commentable-wall-item">
                
                
                <?php  
                if ($posting->getUserInfo()->getAttribute('first_name') == ''){
                            $username = $posting->getUserInfo()->getUserName();
                        }else{
                            $username =  $posting->getUserInfo()->getAttribute('first_name').' '.$posting->getUserInfo()->getAttribute('last_name');
                        }
                        
                        ?>
                        
                        <ul class="cws-wall-user">
                            <li><a href="<?php   echo $profile_url ?>"><strong><?php   echo $username ?></strong></a></li>
                            <li><span class="time"><?php   echo $date->timeSince(strtotime($posting->pCreateDate)) ?> ago<?php  if($u->isRegistered()){?> - <a id="formshow_<?php  echo $posting->pID?>" class="cws-comment-bar" href="javascript:void(0);"><?php  echo t('comment')?></a><?php }?></span> </li>
                            <li><?php if ($sw == '2'){?><i class="icon-globe shared-everyone" title="<?php echo t('shared with everyone')?>"></i><?php }else{?><i class="icon-user shared-friends" title="<?php echo t('shared with friends')?>"></i><?php }?></li>
                            
                            <?php if($u->getUserID() == $user->getUserID() or $u->isSuperUser()){?>
                            <li class="cws-edit-tools">
                            <?php if($posting->getType()->ptName != 'CWS-Photo'){?>
                            <i id="editPostTrigger_<?php echo $posting->pID?>" class="icon-edit cws-edit-post" title="<?php echo t('edit')?>"></i> 
                            <?php }?>
                            <a data-target="#deletePostModal_<?php echo $posting->pID?>" data-toggle="modal">
                            <i class="icon-minus-sign cws-delete-post" title="<?php echo t('delete')?>"></i>
                            </a></li>
                        

                            <?php }?>
                        </ul> 
                    <div class="clearfix"></div>
                    
                    
                    
                    <div id="posting_<?php echo $posting->pID?>" class="cws-posting">
                    <?php   echo $wall->getGraffiti($posting->getType()->ptTemplate,  $posting->pData, $posting->pCreateDate, false) ?>
                    </div>
                    
                    
                    
                    
                    <div id="editPosting_<?php echo $posting->pID?>" class="editPosting hide">
                    <?php if($posting->getType()->ptName == 'CWS-Link'){?>
                        <div class="input-prepend">
                        <span class="add-on"><i class="icon-share"></i></span>
                            <input type="text" class="span2" name="statlink-edit" id="statlink-edit_<?php echo $posting->pID?>" value=""/>
                        </div>
                    <?php }?>
                    
                        <div class="input-prepend input-append">
                        <span class="add-on"><a id="cws-everyone_<?php echo $posting->pID?>" title="<?php  echo t('Everyone')?>" data-placement="left" class="cws-everyone-edit <?php if ($sw == '2'){?>show<?php }?>"><i class="icon icon-globe"></i></a><a id="cws-friends_<?php echo $posting->pID?>" title="<?php  echo t('Friends')?>" data-placement="left" class="cws-friends-edit <?php if ($sw == '1'){?>show<?php }?>"><i class="icon icon-user"></i></a></span>
                        <input type="text" class="span3" name="statext-edit" id="statext-edit_<?php echo $posting->pID?>" value=""/>
                        <input type="hidden" name="sw-edit" id="sw-edit_<?php echo $posting->pID?>" value="<?php echo $sw?>"/>
                        
                        <input type="hidden" name="pType" id="pType_<?php echo $posting->pID?>" value="<?php echo $posting->getType()->ptName?>"/>
            			
            
                        <button type="submit" name="submit" class="btn btn-success cws-post-update-btn" data-placement="bottom" title="<?php echo t('save')?>" id="status-post-edit_<?php echo $posting->pID?>"><i class="icon-white icon-ok-circle"></i></button>
                        <button type="cancel" name="cancel" class="btn post-cancel" title="<?php echo t('cancel')?>" data-placement="bottom"><i class="icon-ban-circle"></i></button>
                        </div>
                    </div>
                    
                    
                    

                    <div class="clearfix"></div>
                 </div>
                <div class="cws-comment-box"> 

				<?php 
				$bt->controller->set('pID', $posting->pID);
				$bt->render('view');
				
				?>
            </div> 
            
            </div>
            
            

                
          <div class="clearfix"></div>       
    
        </li>
        
        
        <div class="modal hide" id="deletePostModal_<?php echo $posting->pID?>" role="dialog" aria-labelledby="PostModal_<?php echo $posting->pID?>" aria-hidden="true" data-backdrop="true">
          <div class="modal-header">
            <h3 id="CommentPost_<?php echo $posting->pID?>"><?php echo t('Delete Post')?></h3>
          </div>
          <div class="modal-body">
			
            <p><?php echo t('Are you sure that you want to delete this post?<br/>This action will also delete any comments associated with this post.')?></p>
          </div>
          <div class="modal-footer">
          	<form id="cws-post-delete-form" method="post" action="">
          	
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo t('Cancel')?></button>
            <button id="deletePost_<?php echo $posting->pID?>" class="btn btn-danger delete-post-btn"><?php echo t('Delete')?></button>
  			</form>
          </div>
        </div>
        
        
        <div class="modal hide" id="deleteCommentModal" role="dialog" aria-labelledby="CommentModal" aria-hidden="true" data-backdrop="true">
          <div class="modal-header">
            <h3 id="CommentModal"><?php echo t('Delete Comment')?></h3>
          </div>
          <div class="modal-body">
            
            <p><?php echo t('Are you sure that you want to delete this comment?')?></p>
          </div>
          <div class="modal-footer">
            <form id="cws-post-delete-form" method="post" action="">
            
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo t('Cancel')?></button>
            <button id="" class="btn btn-danger delete-comment-btn"><?php echo t('Delete')?></button>
            </form>
          </div>
        </div>
        
       
    	<?php   
			}
		} ?>
</ul>

</div>

<script>
readyCommentStream();
	
</script>


