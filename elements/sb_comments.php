<?php 
defined('C5_EXECUTE') or die("Access Denied."); 
$u = new User();
$cui = UserInfo::getByID($u->getUserID());
$av = Loader::helper('concrete/avatar');
$date = Loader::helper('date');
$token = Loader::helper('validation/token');
?>
<div class="clear"></div>
  <div class="cws-comments_<?php  echo $pID?> cws-comments">
  	<div class="cws-user-comments">
                 
<?php  		
for($i = 0;$i < count($comments);$i++){
$comui = UserInfo::getByID($comments[$i]['uID']);
?>

<div id ="cws-wall-post-comment_<?php echo $comments[$i]['commentID']?>" class="cws-wall-post-comment">
	<ul class="cws-wall-user">
		<li class="comment-user-img"><?php echo $av->outputUserAvatar($comui,false,0.35)?></li>
                        
<?php
	if ($comui->getAttribute('first_name') == ''){
		$commname =  $comui->getUserName();
	}else{
		$commname = $comui->getAttribute('first_name').' '.$comui->getAttribute('last_name');
	}
?>

			<li class="comment-user-name"><a href="<?php echo View::url('/profile',$comments[$i]['uID'])?>"><?php echo $commname?></a></li>
      <li><span class="time">(<?php echo $date->timeSince(strtotime($comments[$i]['entryDate']))?> ago)</span></li>
					
       
                   
      <li class="cws-edit-tools">
      
      <?php  if($u->getUserID() == $comments[$i]['uID']){?> 
      <i id="cwsEdit_<?php echo $comments[$i]['commentID']?>"class="icon-edit cws-edit-comment" title="<?php echo t('edit')?>"></i> 
      <?php }?>
      
	  <?php  if($u->getUserID() == $comments[$i]['uID'] or $u->getUserID() == $postUserID){?> 
      <a href="#deleteCommentModal" data-commid="<?php echo $comments[$i]['commentID']?>" data-toggle="modal" class="commButtonToggle"><i class="icon-minus-sign cws-delete-comment" title="<?php echo t('delete')?>"></i></a>
      <?php }?>
      </li>
      
      
     </ul>
<div class="clearfix"></div>

	<p class="wall-posting-comment"><?php echo stripslashes($comments[$i]['commentText'])?></p>

<div class="clearfix"></div>
</div>


<div id="editComment_<?php echo $comments[$i]['commentID']?>" class="editComment hide">


		<div class="input-prepend input-append">
		<span class="add-on"><i class="icon-comment"></i></span>
		<input type="text" class="span8" name="comment-edit" id="comment-edit_<?php echo $comments[$i]['commentID']?>" value="<?php echo $comments[$i]['commentText']?>"/>
    <input type="hidden" id="commpID_<?php echo $comments[$i]['commentID']?>" name="commpID_<?php echo $comments[$i]['commentID']?>" value="<?php  echo $pID?>"/>

		<button type="submit" name="submit" class="btn btn-success cws-comment-update-btn" data-placement="bottom" title="<?php echo t('save')?>" id="post-comment-edit_<?php echo $comments[$i]['commentID']?>"><i class="icon-white icon-ok-circle"></i></button>
		<button type="cancel" name="cancel" class="btn comment-cancel" title="<?php echo t('cancel')?>" data-placement="bottom"><i class="icon-ban-circle"></i></button>
		</div>
</div>
                        
                        
<?php }?>
                    
</div>

<?php  if($u->isRegistered()){?>
<div id="cws-comment-form_<?php  echo $pID?>" class="cws-comment-form">
	<div class="comment-form">
		<form id="CommentAddForm_<?php  echo $pID?>" method="post" action="">
			<div class="input-prepend input-append">
        <span class="add-on"><?php  echo  $av->outputUserAvatar($cui,false,0.25)?></span>
        <input type="text" class="cwsComment span4" name="cwsComment_<?php  echo $pID?>" id="cwsComment_<?php  echo $pID?>"/>
        <button type="submit" class="btn cws-comment-link form-button" id="cws-comment-link_<?php  echo $pID?>" onclick="postComment(<?php  echo $pID?>);return false;"><?php echo t('Post')?></button>
       </div>
    </form>
	</div>
</div>

<?php  }else{?>

<a href="<?php echo $this->url('login')?>" class="btn btn-mini"><?php echo t('Login to post a comment')?></a>

<?php }?>
</div>

<script>
readyCommentStream();
	
</script>



