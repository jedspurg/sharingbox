<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
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
	

