<?php

class SharingboxPost extends Model{
	
	protected $db;
	
	public function __construct(){
		parent::__construct();
		
		$this->db = Loader::db();
		
		
	}
	
	public function savePost($uID, $post, $share_with, $post_template){		
		$data = array($uID, $post, $share_with, $post_type);
		$sql='INSERT INTO SharingboxPosts (uID,post,shareWith,postTemplate) VALUES (?,?,?,?)'; 
		$this->db->execute($sql,$data);		
	}
	
	public function getPosts($limit = 0, $uID = null){
		
		$sql = "SELECT * FROM SharingboxPosts";
		if($uID){$sql .= " WHERE uID = '{$uID}'";}
		$sql .= " LIMIT {$limit}, 20";
		
		$results = $this->db->query($sql);
		
		while ($row=$results->fetchrow()) {
			$posts[] = $row;
		}

		
		return $posts;
	}
	
}