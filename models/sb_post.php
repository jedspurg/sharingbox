<?php

class SharingboxPost extends Model{
	
	public function save_post($uID, $post, $share_with, $post_type){
		
		$db = Loader::db();
		$data = array($uID, $post, $share_with, $post_type);
		$sql='INSERT INTO SharingboxPosts (uID,post,shareWith,postType) VALUES (?,?,?,?)'; 
		$db->execute($sql,$data);
		
		
	}
	
}