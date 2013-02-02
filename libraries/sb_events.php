<?php
class SbEvents{
	
	
	
	public function friend_add($uID, $friendUID){
		
		$user_friend_add = array('c5_wall_share', 'friend_added', 'CWS-Friend-Added', '%1$s', 1, 2);
		$u=new User();
		$sw = 2;
		$friendui = UserInfo::getByID($friendUID);
		
		if ($friendui->getAttribute('first_name') == ''){
			$friendname =  $friendui->getUserName();
		}else{
			$friendname = $friendui->getAttribute('first_name').' '.$friendui->getAttribute('last_name');
		}
		$wall_post = '<span class="cws-status-post">'. t('is now friends with') .' <a href="'.View::url('/profile',$friendUID).'">'.$friendname.'</a></span>';
		$wall = Loader::package('lerteco_wall');
	
		if (is_object($wall)) {
			$wall->postAndPossiblyRegister($u->getUserID(), array($wall_post, $sw), $user_friend_add);
		}
		
	}
	
}
