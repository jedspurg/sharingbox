<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
class SharingboxBlockController extends BlockController {
	

	
	protected $btTable = 'btSharingbox';
	protected $btInterfaceWidth = "550";
	protected $btInterfaceHeight = "200";
	protected $posting_type_status = array('c5_wall_share', 'status_share', 'CWS-Status', '%1$s', 1, 2);
	protected $posting_type_link = array('c5_wall_share', 'link_share', 'CWS-Link', '%1$s', 1, 2);
	protected $posting_type_photo = array('c5_wall_share', 'photo_share', 'CWS-Photo', '%1$s', 1, 2);


	/** 
	 * Used for localization. If we want to localize the name/description we have to include this
	 */
	public function getBlockTypeDescription() {
		return t("Share Status and Links socially.");
	}
	
	public function getBlockTypeName() {
		return t("SharingBox");
	}
	

	
	function __construct($obj = null) {		
		parent::__construct($obj);
		$this->db = Loader::db();
		$u = new User();

	}	
	
	function view() { 
	
	}

	function on_page_view() {
		$html = Loader::helper('html');
		$b = Block::getByName('sharingbox');
		$this->addFooterItem('
		<script type="text/javascript">
		var CWS_TOOLS_DIR = "'.Loader::helper('concrete/urls')->getBlockTypeToolsURL($b).'sharingbox/";
		var CWS_COMMENT_HELPER = "'.Loader::helper('concrete/urls')->getToolsURL('add_comment', 'sharingbox').'";
		var CWS_UPDATE_COMMENT = "'.Loader::helper('concrete/urls')->getToolsURL('update_comment', 'sharingbox').'";
		var CWS_POST_UPDATE = "'.Loader::helper('concrete/urls')->getToolsURL('update_post', 'sharingbox').'";
		var CWS_POST_DELETE = "'.Loader::helper('concrete/urls')->getToolsURL('delete_post', 'sharingbox').'";
		var CWS_COMMENT_DELETE = "'.Loader::helper('concrete/urls')->getToolsURL('delete_comment', 'sharingbox').'";
		</script>');
		
 		$gallerybox = Loader::package('gallerybox'); 
	    if (is_object($gallerybox)) {
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
	

			$this->addFooterItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/i18n_js"></script>'); 
			$this->addFooterItem($html->javascript('jquery.ui.js'));
			$this->addFooterItem($html->javascript('jquery.form.js'));
			$this->addFooterItem($html->javascript('jquery.rating.js'));
			$this->addFooterItem($html->javascript('bootstrap.js'));
			$this->addFooterItem($html->javascript('ccm.app.js'));
			
	
			global $c;
			$cID = $c->getCollectionID();

			$this->addFooterItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/page_controls_menu_js?cID=' . $cID . '&amp;cvID=' . $cvID . '&amp;btask=' . $_REQUEST['btask'] . '&amp;ts=' . time() . '"></script>'); 

			$this->addFooterItem($html->javascript('user.filemanager.js', 'gallerybox'));

			$this->addFooterItem('<script type="text/javascript">$(function() { ccm_activateFileManager(\'DASHBOARD\', \'' . $searchInstance . '\'); });</script>');
		
			$this->addHeaderItem('<script type="text/javascript">
			var CWS_UPLOAD_TOOL = "'.Loader::helper('concrete/urls')->getToolsURL('photo_share', 'sharingbox').'";
			var CWS_UPLOAD_COMPLETE = "'.Loader::helper('concrete/urls')->getToolsURL('photo_uploaded', 'sharingbox').'";
			var GBX_SET_TOOL = "'.Loader::helper('concrete/urls')->getToolsURL('user_add_to', 'gallerybox').'";
			var GBX_COMPLETED_TOOL = "'.Loader::helper('concrete/urls')->getToolsURL('add_to_complete', 'gallerybox').'";
			var GBX_SET_RELOAD = "'.Loader::helper('concrete/urls')->getToolsURL('search_user_sets_reload', 'gallerybox').'";
			</script>');
		
		}

	}

	function delete(){
	}
	
	private function getPageAreaBlock($area, $cID){
			$c = Page::getByID($cID);
			$a = new Area($area);
			$blocks = $a->getAreaBlocksArray($c);
			if(is_array($blocks) && count($blocks) > 0) {
				foreach($blocks as $b) {
					if($b->getBlockTypeHandle() == 'lerteco_wall') {
						$bID = $b->getBlockID();
						$block = Block::getByID($bID, $c, $area);
						return $block;
					}	
				}
			}
	}
	
	public function status_share($statext, $sw){
	$u = new User();
	if($u->isRegistered()){	
		$statext = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', '<a href="\0" target="_blank">\4</a>', strip_tags($statext) );
		
		Loader::model('sb_post','sharingbox');
		$poster = new SharingboxPost();
		$wall_status = $this->prep_status_share($statext);
		$poster->save_post($u->getUserID(), $wall_status, $sw, 1);
		
		
		
			
			if ($_REQUEST['ajax'] == true) {
				Loader::packageElement('sb_postings','sharingbox');	
				exit;
			} 
		}
	}
	
	public function link_share($statext, $statlinkcomment, $sw, $blockArea, $cID){
	
	$u=new User();
	if($u->isRegistered()){	
		$wall = Loader::package('lerteco_wall');
		
		if (is_object($wall)) {
			
			$wall_link = $this->prep_link_share($statext, $statlinkcomment);
			$wall->postAndPossiblyRegister($u->getUserID(), array($wall_link, $sw), $this->posting_type_link);
			
		}
			
			$b = $this->getPageAreaBlock($blockArea, $cID);
			if ($_REQUEST['ajax'] == true) {	
				Loader::packageElement('test','sharingbox');	
				//$b->display('templates/commentable');
				exit;
			}
		}
	}
	
	public function update_link_share($pID, $statext, $statlinkcomment, $sw, $blockArea, $cID){
		
		$wall_link = $this->prep_link_share($statext, $statlinkcomment);
		$data = array($wall_link,$sw);
		$unsanitized = serialize($data);
		$pData = mysql_real_escape_string($unsanitized);
		$db = Loader::db();
		$sql="UPDATE LWPostings SET pData = '$pData' WHERE pID = '$pID'"; 
		$db->execute($sql);
		
		$b = $this->getPageAreaBlock($blockArea, $cID);
		if ($_REQUEST['ajax'] == true) {	
			Loader::packageElement('test','sharingbox');	
				//$b->display('templates/commentable');
			exit;
		}

	}
	
	public function update_status_share($pID, $statext, $sw, $blockArea, $cID){

		$wall_status = $this->prep_status_share($statext);
		$data = array($wall_status,$sw);
		$unsanitized = serialize($data);
		$pData = mysql_real_escape_string($unsanitized);
		$db = Loader::db();
		$sql="UPDATE LWPostings SET pData = '$pData' WHERE pID = '$pID'"; 
		$db->execute($sql);
		
		$b = $this->getPageAreaBlock($blockArea, $cID);
		if ($_REQUEST['ajax'] == true) {	
			$b->display('templates/commentable');
			exit;
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
	
	
	
	public function delete_post($pID){
		
		
		$db = Loader::db();
		$sql="DELETE FROM LWPostings WHERE pID = '$pID'"; 
		$db->execute($sql);
		
		$sql="DELETE FROM btCWShareComments WHERE pID = '$pID'"; 
		$db->execute($sql);
	
 
	}
	
	
	
	
	
	public function photo_share($uID,$numFiles, $sw, $blockArea, $cID){
		Loader::helper('concrete/file');
		Loader::model('file_attributes');
		Loader::library('file/types');
		Loader::model('file_list');
		Loader::model('file_set');
		
	
		$wall = Loader::package('lerteco_wall');
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

		
	if (is_object($wall) && $numFiles > 0) {

	$wall->postAndPossiblyRegister($uID, array($sharedImageList, 2), $this->posting_type_photo);
	}
		
			$b = $this->getPageAreaBlock($blockArea, $cID);
			if ($_REQUEST['ajax'] == true) {	
				$b->display('templates/commentable');
				exit;
			}
	}
	
	
	
	private function getUrlData($url){
		$result = false;
		$extpage = $this->getUrlContents($url);
		if (isset($extpage['content']) && is_string($extpage['content'])){
			$title = null;
			$metaTags = null;
			preg_match('/<title>([^>]*)<\/title>/si', $extpage['content'], $match );
			if (isset($match) && is_array($match) && count($match) > 0)
			{
				$title = strip_tags($match[1]);
			}
			preg_match_all('/<[\s]*meta[\s]*name="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $extpage['content'], $match);
		   
			if (isset($match) && is_array($match) && count($match) == 3)
			{
				$originals = $match[0];
				$names = $match[1];
				$values = $match[2];
			   
				if (count($originals) == count($names) && count($names) == count($values))
				{
					$metaTags = array();
				   
					for ($i=0, $limiti=count($names); $i < $limiti; $i++)
					{
						$metaTags[strtolower($names[$i])] = array (
							'html' => htmlentities($originals[$i]),
							'value' => $values[$i]
						);
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
	
	private function getUrlContents($url)
	{
		$result = false;

		$options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    	);
		$ch = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err  = curl_errno( $ch );
		$errmsg = curl_error( $ch );
		$header = curl_getinfo( $ch );
		curl_close( $ch );
	
		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
	}
	
		public function getPostUserID($pID){
		$db = Loader::db();
		$ic = $db->query("SELECT pUID FROM LWPostings where pID = '$pID'");
		$row = $ic->fetchrow();
		$uID = $row['pUID'];
		return $uID;
	}
	
	public function getComments($pID){
		$db = Loader::db();
		$ic = $db->query("SELECT * FROM btCWShareComments where pID = '$pID'");
		while($row=$ic->fetchrow()){
			$comments[] = $row;
		}		

		return $comments;
	}
	
	function add_comment($pID, $comtext){
	$u=new User();
	if($u->isRegistered()){	
		$comtext = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', '<a href="\0" target="_blank">\4</a>', $comtext );
	
			$data = array('pID' => $pID, 'comUID' => $u->getUserID(), 'cwsComment' => addslashes($comtext));
			$this->saveComment($data);
			if ($_REQUEST['ajax'] == true) {
					$bt = BlockType::getByHandle('cws_comments');
					$bt->controller->set('pID', $pID);
					$bt->render('view');
				exit;
			} 
	}
		

		
	}
	
	private function saveComment($data) {
		$u = new User();
		if($u->isRegistered()){
			$db= Loader::db();
			$q = ("INSERT INTO btCWShareComments (pID, uID, commentText) VALUES (?,?,?)");
			$db->EXECUTE($q,$data);
		}


	}
	
	public function delete_comment($commID){
		$db = Loader::db();
		$sql="DELETE FROM btCWShareComments WHERE commentID = '$commID'"; 
		$db->execute($sql);
	
 
	}
	
	public function update_comment($pID, $commID, $commText){
		$commText = addslashes($commText);
		$db = Loader::db();
		$sql="UPDATE btCWShareComments SET commentText = '$commText' WHERE commentID = '$commID'"; 
		$db->execute($sql);
		
		if ($_REQUEST['ajax'] == true) {
					$bt = BlockType::getByHandle('cws_comments');
					$bt->controller->set('pID', $pID);
					$bt->render('view');
				exit;
			} 
	
 
	}

	
	
}

?>
