<?php
defined('C5_EXECUTE') or die("Access Denied.");

class SharingboxPost extends Model{
	
	protected $db;
	
	public function __construct(){
		parent::__construct();
		$this->db = Loader::db();
	}
	
	public function getPosts($offset, $uID){
		$sql = "SELECT * FROM SharingboxPosts";
		if($uID != 0){$sql .= " WHERE uID = $uID";}
		$sql .= " ORDER BY entryDate DESC";
		$sql .= " LIMIT $offset, 10";
		$results = $this->db->query($sql);
		$returnArray = array();
		while ($row=$results->fetchrow()) {
			$returnArray[] = $row;
		}
		$posts = array();
		foreach($returnArray as $p){
			$post = new StdClass;
			$post->pID = $p['pID'];
			$post->uID = $p['uID'];
			$post->post = $p['post'];
			$post->sw = $p['shareWith'];
			$post->created = $p['entryDate'];
			$post->updated = $p['updatedDate'];
			$post->ptID = $this->getPostTemplateID($p['postTemplate']);
			$post->ptHandle = $p['postTemplate'];
			$posts[] = $post;
		}
		return $posts;
	}
	
	public function getPosterUserID($pID){
		$sql="SELECT uID FROM SharingboxPosts where pID = ?";
		$ic = $this->db->query($sql, array($pID));
		$row = $ic->fetchrow();
		$uID = $row['uID'];
		return $uID;
	}
	
	public function savePost($uID, $post, $share_with, $post_template){		
		$data = array($uID, $post, $share_with, $post_template);
		$sql='INSERT INTO SharingboxPosts (uID,post,shareWith,postTemplate,entryDate,updatedDate) VALUES (?,?,?,?,NOW(),NOW())'; 
		$this->db->execute($sql,$data);		
	}
	
	public function deletePost($pID){
		$sql="DELETE FROM SharingboxPosts WHERE pID = ?"; 
		$this->db->execute($sql, array($pID));
		$sql="DELETE FROM SharingboxComments WHERE pID = ?'"; 
		$this->db->execute($sql, array($pID));
	}
	
	public function updatePost($pID, $post, $sw){
		$data = array($post, $sw, $pID);
		$sql = "UPDATE SharingboxPosts SET post=?, shareWith=? , updatedDate = NOW() WHERE pID=?";
		$this->db->execute($sql,$data);
	}
	
	public function getPostTemplateID($ptHandle){
		$sql = "SELECT templateID FROM SharingboxPostTemplates WHERE handle = ?";
		$row = $this->db->getrow($sql, array($ptHandle));
		$result = $row['templateID'];
		return $result;
	}
	
	public function getComments($pID){
		$sql="SELECT * FROM SharingboxComments where pID = ?";
		$ic = $this->db->query($sql, array($pID));
		while($row=$ic->fetchrow()){
			$comments[] = $row;
		}		
		return $comments;
	}
	

	public function saveComment($data){
		$sql = ("INSERT INTO SharingboxComments (pID, uID, commentText) VALUES (?,?,?)");
		$this->db->EXECUTE($sql,$data);
	}
	
	public function updateComment($pID, $commID, $commText){
		$sql="UPDATE SharingboxComments SET commentText = ? WHERE commentID = ?"; 
		$this->db->execute($sql, array($commText,$commID));
	}
	
	public function deleteComment($commID){
		$sql="DELETE FROM SharingboxComments WHERE commentID = ?"; 
		$this->db->execute($sql, array($commID));
	}
	
	
	
}