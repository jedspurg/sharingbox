<?php
defined('C5_EXECUTE') or die("Access Denied.");
Loader::model('sb_post','sharingbox');
class SbEvents{
	
	public function friend_add($uID, $friendUID){
		$u = new User();
		$handle = 'friend_added';
		$sw = 1;
		$poster = new SharingboxPost();
		$friendui = UserInfo::getByID($friendUID);
		
		if ($friendui->getAttribute('first_name') == ''){
			$friendname =  $friendui->getUserName();
		}else{
			$friendname = $friendui->getAttribute('first_name').' '.$friendui->getAttribute('last_name');
		}
		$wall_post = '<span class="cws-status-post">'. t('is now friends with') .' <a href="'.View::url('/profile',$friendUID).'">'.$friendname.'</a></span>';
	
		$poster->savePost($u->getUserID(), $wall_post, $sw, $handle);

		
	}

	public function user_delete($user){
		Loader::model('sb_post','sharingbox');
		$sbModel = new SharingboxPost();
		$uID = $user->getUserID();
		$sbModel->deleteUserPostsAndComments($uID);
	}
	
}
