<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('sb_post','sharingbox');
class SharingboxBlockController extends BlockController {
	
	protected $btTable = 'btSharingbox';
	protected $btInterfaceWidth = "550";
	protected $btInterfaceHeight = "200";
	
	public function getBlockTypeDescription() {
		return t("Share Status and Links socially.");
	}
	
	public function getBlockTypeName() {
		return t("SharingBox");
	}
	
	public function add() {
    	$this->set('visibility', 1);
	}
	
	private function loadBlockInformation() {
		$this->set('uID', $this->uID);
		$this->set('type', $this->type);	
		$this->set('visibility', $this->visibility);	
		$this->set('bID', $this->bID);				
	}
		
	private function getPosts($offset = 0, $sbUID = 0){
		$sbModel = new SharingboxPost();
		return $sbModel->getPosts($offset,$sbUID);
	}
	
	public function loadMorePosts($offset, $sbUID){
		if ($_REQUEST['ajax'] == true) {
				print Loader::packageElement('sb_postings_more','sharingbox', array('postings'=>$this->getPosts($offset, $sbUID)));	
				exit;
			}
	}

	public function on_page_view() {
		$html = Loader::helper('html');
		$token = Loader::helper('validation/token');
		$b = Block::getByName('sharingbox');
		$this->loadBlockInformation(); 
		switch ($this->type){
			case '1':
				$view = View::getInstance(); 
				if ( is_object($view) && is_object($view->controller) && is_object($view->controller->getvar('profile')) ){ 
					$userInfo = $view->controller->getvar('profile'); 
					$sbUID = $userInfo->getUserID();
				}else{ 
					$sbUID=0;
				}
			break;
			case '2':
				$sbUID = $this->uID;
			break;
			case '3':
				$sbUID=0;
			break;
		}
		$this->set('postings', $this->getPosts(0,$sbUID));
		
		$this->addFooterItem('
		<script type="text/javascript">
		var SB_TOOLS_DIR = "'.Loader::helper('concrete/urls')->getToolsURL(null, 'sharingbox').'";
		var SB_COMMENT_HELPER = "'.Loader::helper('concrete/urls')->getToolsURL('add_comment', 'sharingbox').'";
		var SB_UPDATE_COMMENT = "'.Loader::helper('concrete/urls')->getToolsURL('update_comment', 'sharingbox').'";
		var SB_POST_UPDATE = "'.Loader::helper('concrete/urls')->getToolsURL('update_post', 'sharingbox').'";
		var SB_POST_DELETE = "'.Loader::helper('concrete/urls')->getToolsURL('delete_post', 'sharingbox').'";
		var SB_COMMENT_DELETE = "'.Loader::helper('concrete/urls')->getToolsURL('delete_comment', 'sharingbox').'";
		var SB_POST_LOADER = "'.Loader::helper('concrete/urls')->getToolsURL('post_loader', 'sharingbox').'";
		var offset = 0;
		var sbUID = '.$sbUID.';
		var valt = '. $token->generate() .';
		</script>');
		
		$gallerybox = Loader::package('gallerybox'); 
		if (is_object($gallerybox)):
			$this->set('form', $form);
			$searchInstance = 'gbx' . time();
			$this->set('searchInstance', $searchInstance);

			$this->addHeaderItem($html->css('jquery.rating.css'));
			$this->addHeaderItem($html->css('ccm.dialog.css'));
			$this->addHeaderItem($html->css('ccm.menus.css'));
			$this->addHeaderItem($html->css('ccm.forms.css'));
			$this->addHeaderItem($html->css('ccm.search.css'));
			$this->addHeaderItem($html->css('ccm.filemanager.css'));
			$this->addHeaderItem($html->css('jquery.ui.css'));
			
			$this->addHeaderItem('<script type="text/javascript">
			var SB_UPLOAD_COMPLETE = "'.Loader::helper('concrete/urls')->getToolsURL('photo_uploaded', 'sharingbox').'";
			var GBX_SET_TOOL = "'.Loader::helper('concrete/urls')->getToolsURL('user_add_to', 'gallerybox').'";
			var GBX_COMPLETED_TOOL = "'.Loader::helper('concrete/urls')->getToolsURL('add_to_complete', 'gallerybox').'";
			var GBX_SET_RELOAD = "'.Loader::helper('concrete/urls')->getToolsURL('search_user_sets_reload', 'gallerybox').'";
			</script>');
	
			$c = Page::getCurrentPage();
			$cID = $c->getCollectionID();

			$this->addFooterItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/page_controls_menu_js?cID=' . $cID . '&amp;cvID=' . $cvID . '&amp;btask=' . $_REQUEST['btask'] . '&amp;ts=' . time() . '"></script>'); 
			$this->addFooterItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/i18n_js"></script>'); 
			$this->addFooterItem($html->javascript('jquery.ui.js'));
			$this->addFooterItem($html->javascript('jquery.form.js'));
			$this->addFooterItem($html->javascript('jquery.rating.js'));
			$this->addFooterItem($html->javascript('bootstrap.js'));
			$this->addFooterItem($html->javascript('ccm.app.js'));
			$this->addFooterItem($html->javascript('user.filemanager.js', 'gallerybox'));
			$this->addFooterItem($html->javascript('swfupload/swfupload.js'));
			$this->addFooterItem($html->javascript('swfupload/swfupload.handlers.js'));
			$this->addFooterItem($html->javascript('swfupload/swfupload.fileprogress.js'));
			$this->addFooterItem($html->javascript('swfupload/swfupload.queue.js'));
			$this->addFooterItem('<script type="text/javascript">$(function() { ccm_activateFileManager(\'DASHBOARD\', \'' . $searchInstance . '\'); });</script>');	
		endif;

	}
	
	public function status_share($statext, $sw, $sbUID, $valt){
		$u = new User();
		$sbModel = new SharingboxPost();
		$vt = Loader::helper('validation/token');
		if($u->isRegistered() && $vt->validate($valt)){	
			$statext = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', '<a href="\0" target="_blank">\4</a>', strip_tags($statext) );
			$handle = 'sb_status';
			$wall_status = $this->prep_status_share($statext);
			$sbModel->savePost($u->getUserID(), $wall_status, $sw, $handle);
			if ($_REQUEST['ajax'] == true) {
				Loader::packageElement('sb_postings','sharingbox', array('postings'=>$this->getPosts(0, $sbUID)));	
				exit;
			} 
		}
	}

	
	public function link_share($statext, $statlinkcomment, $sw, $sbUID, $valt){
		$u = new User();
		$sbModel = new SharingboxPost();
		$vt = Loader::helper('validation/token');
		if($u->isRegistered()){	
			$handle = 'sb_link';
			$wall_link = $this->prep_link_share($statext, $statlinkcomment);
			if ($vt->validate($valt)){
				$sbModel->savePost($u->getUserID(), $wall_link, $sw, $handle);
				if ($_REQUEST['ajax'] == true) {
					Loader::packageElement('sb_postings','sharingbox', array('postings'=>$this->getPosts(0, $sbUID)));	
					exit;
				}
			}
		}
	}
	
	public function update_link_share($pID, $statext, $statlinkcomment, $sw, $sbUID, $valt){
		$vt = Loader::helper('validation/token');
		$sbModel = new SharingboxPost();
		$wall_link = $this->prep_link_share($statext, $statlinkcomment);
		if ($vt->validate($valt)){	
			$sbModel->updatePost($pID, $wall_link, $sw);
			if ($_REQUEST['ajax'] == true) {
				Loader::packageElement('sb_postings','sharingbox', array('postings'=>$this->getPosts(0, $sbUID)));	
				exit;
			}
		}
	}
	
	public function update_status_share($pID, $statext, $sw, $sbUID, $valt){
		$vt = Loader::helper('validation/token');
		$sbModel = new SharingboxPost();
		$statext = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', '<a href="\0" target="_blank">\4</a>', strip_tags($statext) );
		$wall_status = $this->prep_status_share($statext);
		if ($vt->validate($valt)){	
			$sbModel->updatePost($pID, $wall_status, $sw);
			if ($_REQUEST['ajax'] == true) {
				Loader::packageElement('sb_postings','sharingbox', array('postings'=>$this->getPosts(0, $sbUID)));	
				exit;
			}
		}
	}
	
	public function delete_post($pID, $valt){
		$vt = Loader::helper('validation/token');
		if ($vt->validate($valt)){
			$sbModel = new SharingboxPost();
			$sbModel->deletePost($pID);
		}
	}
	
	private function prep_status_share($statext){
		$wall_status = '<span class="cws-status-post">'.$statext.'</span>';
		return $wall_status;
	}
	
	private function prep_link_share($statext, $statlinkcomment){
		if (strpos($statext,'http://') === false && strpos($statext,'https://') === false){
				  $statext = 'http://'.$statext;
		}
		$urlData = $this->getUrlData($statext);
		if ($urlData['title'] == ''){
			$link_title = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', '\4', $statext );
		}else{
			$link_title = $urlData['title'];
		}
		$link_desc = $urlData['metaTags']['description']['value'];
		
		$wall_link ='<div class="cws-wall-link-comment">'.strip_tags($statlinkcomment).'</div><div class="cws-wall-link"><p><i class="icon-bookmark"></i> <a href="'.$statext.'" target="_blank"><strong>'.$link_title.'</strong></a><br/><span class="link-desc">'.$link_desc.'</span></p></div>';
		
		return $wall_link;
		
	}
	
	public function photo_share($uID,$numFiles,$sw){
		Loader::helper('concrete/file');
		Loader::model('file_attributes');
		Loader::library('file/types');
		Loader::model('file_list');
		Loader::model('file_set');
		$sbModel = new SharingboxPost();
		$handle = 'sb_photo';
		
		$fs = FileSet::getByName('user_gallery_'.$uID);
		$fileList = new FileList();		
		$fileList->filterBySet($fs);
		$fileList->filterByType(FileType::T_IMAGE);	

		$fldca = new FileManagerAvailableColumnSet();

		$columns = new FileManagerColumnSet();
		$sortCol = $fldca->getColumnByKey('fDateAdded');
		$columns->setDefaultSortColumn($sortCol, 'desc');
		$col = $columns->getDefaultSortColumn();	
		$fileList->sortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
			if($numFiles > 3){
				$fileCount = 3;
			}else{
				$fileCount = $numFiles;
			}
		$fileList->setItemsPerPage($fileCount);
		$files = $fileList->getPage();
		$ih =Loader::helper('image');
		if($numFiles == 1){
			$userText = 'uploaded a photo ';
		}else{
			$userText = 'uploaded some photos ';
		}
		$imgui = UserInfo::getByID($uID);
			if ($imgui->getAttribute('first_name') == ''){
				$username =  $imgui->getUserName();
			}else{
				$username = $imgui->getAttribute('first_name').' '.$imgui->getAttribute('last_name');
		}
		$sharedImageList = '<div class="cws-status-post">';
		$sharedImageList .= $userText;
		$sharedImageList .=' - view <a href="'.View::url('/gallerybox/user',$uID).'">'.$username.'\'s gallery</a></div>
		<div class="cws-photo-share-wrapper">';
		foreach($files as $img){

			$sharedImageList .='<div class="cws-photo-item"><div class="cws-img-wrapper"><a href="'.View::url('/gallerybox/image',$img->getFileID()).'">';
			$fv = $img->getApprovedVersion();
			$imgt = $fv->getTitle();
			$imgThumb = $ih->getThumbnail($img,488,9999);
			
			if((($imgThumb->height - 200)/2) > 0){
				$margin = '-'.($imgThumb->height - 200)/2;
			}else{
				$margin = '0';
			}

			$sharedImageList .= '<img src="'.$imgThumb->src.'" style="margin-top:'.$margin.'px" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" title="'.str_replace('"',"'",$imgt).'"/></a></div>';
			$sharedImageList .='</div>';			
		}
		$sharedImageList .='</div><div class="clear"></div>';

		$sbModel->savePost($uID, $sharedImageList, $sw, $handle);
		if ($_REQUEST['ajax'] == true) {
			Loader::packageElement('sb_postings','sharingbox', array('postings'=>$this->getPosts()));	
			exit;
		} 
	}
	
	private function getUrlData($url){
		$result = false;
		$extpage = $this->getUrlContents($url);
		if (isset($extpage) && is_string($extpage)){
			$title = null;
			$metaTags = null;
			
			preg_match('/<title>([^>]*)<\/title>/si', $extpage, $match );
			if (isset($match) && is_array($match) && count($match) > 0){
				$title = strip_tags($match[1]);
			}
			preg_match_all('/<[\s]*meta[\s]*name="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $extpage, $match);
		   
			if (isset($match) && is_array($match) && count($match) == 3){
				$originals = $match[0];
				$names = $match[1];
				$values = $match[2];
			   
				if (count($originals) == count($names) && count($names) == count($values)){
					$metaTags = array();

					for ($i=0, $limiti=count($names); $i < $limiti; $i++){
						$metaTags[strtolower($names[$i])] = array ('html' => htmlentities($originals[$i]),'value' => $values[$i]);
					}
				}
			}
		   
			$result = array (
				'title' => $title,
				'metaTags' => $metaTags
			);
		}	   
		return $result;
	}
	
	private function getUrlContents($url){
		$fh = Loader::helper('file');
		$content = $fh->getContents($url);
		return $content;
	}
	
	public function getPostUserID($pID){
		$sbModel = new SharingboxPost();
		return $sbModel->getPosterUserID($pID);
	}
	
	public function getComments($pID){
		$sbModel = new SharingboxPost();
		return $sbModel->getComments($pID);
	}
	
	public function add_comment($pID, $comtext, $valt){
		$u = new User();
		$vt = Loader::helper('validation/token');
		if($u->isRegistered() && $vt->validate($valt)){	
			$comtext = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', '<a href="\0" target="_blank">\4</a>', strip_tags($comtext) );
			$data = array('pID' => $pID, 'comUID' => $u->getUserID(), 'cwsComment' => $comtext);
			$this->saveComment($data);
			if ($_REQUEST['ajax'] == true) {
				$comments = $this->getComments($pID);
				$postUserID = $this->getPostUserID($pID);
				Loader::packageElement('sb_comments','sharingbox', array('comments'=>$comments,'postUserID'=>$postUserID,'pID'=>$pID));
				exit;
			} 
		}
	}
	
	private function saveComment($data, $valt) {
		$u = new User();
		$vt = Loader::helper('validation/token');
		$sbModel = new SharingboxPost();
		if($u->isRegistered() && $vt->validate($valt)){
			$sbModel->saveComment($data);
		}
	}
	
	public function deleteComment($commID, $valt){
		$vt = Loader::helper('validation/token');
		if ($vt->validate($valt)){
			$sbModel = new SharingboxPost();	
			$sbModel->deleteComment($commID);
		}
	}
	
	public function updateComment($pID, $commID, $commText, $valt){
		$vt = Loader::helper('validation/token');
		$sbModel = new SharingboxPost();
		$commText = addslashes($commText);
		if ($vt->validate($valt)){
			$sbModel->updateComment($pID, $commID, $commText);
			if ($_REQUEST['ajax'] == true) {
					$comments = $this->getComments($pID);
					$postUserID = $this->getPostUserID($pID);
					Loader::packageElement('sb_comments','sharingbox', array('comments'=>$comments,'postUserID'=>$postUserID,'pID'=>$pID));
				exit;
			} 
		}
	}
	
}
