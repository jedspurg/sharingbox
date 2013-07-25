<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$u = new User();
$av = Loader::helper('concrete/avatar');
$date = Loader::helper('date');
Loader::model('users_friends');
$uh = Loader::helper('concrete/urls');
$controller = new SharingboxBlockController();
$vbw = Loader::helper('validation/banned_words');

if(is_array($postings)):
	foreach ($postings as $post):
		$poster = UserInfo::getByID($post->uID);
		$profile_url = View::url('/profile','view', $poster->getUserID());
		$friendsData = UsersFriends::getUsersFriendsData($poster->getUserID());
	
		$sw = $post->sw;
		
		if($sw == '1'):
			for($i = 0;$i < count($friendsData);$i++):
				if($friendsData[$i]['friendUID'] == $u->getUserID()):
						$myfriend = true;
						break;
				 endif;
			endfor;		
		endif;
		
		if($myfriend || $sw == '2' || $u->getUserID() == $poster->getUserID() || $u->isSuperUser()):
?>
      <ul class="sharingbox_wall">
        <li id="cws-item-class_<?php echo $post->pID?>" class="sharingbox-wall-item">
        	<div class="wall-user-img">
            <a href="<?php   echo $profile_url ?>"><?php   echo $av->outputUserAvatar($poster, false, 0.5) ?></a>
            <div class="user-arrow-border"></div>
            <div class="user-arrow"></div>
            </div>
            <div class="commentable-wall-item-container">
                <div class="commentable-wall-item">
 
									<?php  
                  if ($poster->getAttribute('first_name') == ''){
                    $username = $poster->getUserName();
                  }else{
                    $username =  $poster->getAttribute('first_name').' '.$poster->getAttribute('last_name');
                  }
                  ?>
                  <ul class="cws-wall-user">
                    <li><a href="<?php   echo $profile_url ?>"><strong><?php   echo $username ?></strong></a></li>
                    <li><span class="time"><?php   echo $date->timeSince(strtotime($post->created)) ?> ago<?php  if($u->isRegistered()){?> - <a id="formshow_<?php  echo  $post->pID?>" class="cws-comment-bar" href="javascript:void(0);"><?php  echo t('comment')?></a><?php }?></span> </li>
                    <li><?php if ($sw == '2'){?><i class="icon-globe shared-everyone" title="<?php echo t('shared with everyone')?>"></i><?php }else{?><i class="icon-user shared-friends" title="<?php echo t('shared with friends')?>"></i><?php }?></li>
                    
                    <?php if($u->getUserID() == $poster->getUserID() or $u->isSuperUser()):?>
                    <li class="cws-edit-tools">
                    <?php if($post->ptHandle != 'sb_photo'):?>
                    <i id="editPostTrigger_<?php echo $post->pID?>" class="icon-edit cws-edit-post" title="<?php echo t('edit')?>"></i> 
                    <?php endif;?>
                    <a data-target="#deletePostModal_<?php echo $post->pID?>" data-toggle="modal">
                    <i class="icon-minus-sign cws-delete-post" title="<?php echo t('delete')?>"></i>
                    </a></li>
                    <?php endif;?>
                  </ul> 
                  <div class="clearfix"></div>
									
                  <div id="posting_<?php echo $post->pID?>" class="cws-posting">
                    <?php
                    $vbw->hasBannedWords($post->post);
                    echo $post->post; 
                    ?>
                  </div>

                  <div id="editPosting_<?php echo $post->pID?>" class="editPosting hide">
                    <?php if($post->ptHandle == 'sb_link'):?>
                        <div class="input-prepend">
                        <span class="add-on"><i class="icon-share"></i></span>
                            <input type="text" class="span2" name="statlink-edit" id="statlink-edit_<?php echo $post->pID?>" value=""/>
                        </div>
                    <?php endif;?>
                    
                        <div class="input-prepend input-append">
                        <span class="add-on"><a id="cws-everyone_<?php echo $post->pID?>" title="<?php  echo t('Everyone')?>" data-placement="left" class="cws-everyone-edit <?php if ($sw == '2'){?>show<?php }?>"><i class="icon icon-globe"></i></a><a id="cws-friends_<?php echo $post->pID?>" title="<?php  echo t('Friends')?>" data-placement="left" class="cws-friends-edit <?php if ($sw == '1'){?>show<?php }?>"><i class="icon icon-user"></i></a></span>
                        <input type="text" class="span3" name="statext-edit" id="statext-edit_<?php echo $post->pID?>" value=""/>
                        <input type="hidden" name="sw-edit" id="sw-edit_<?php echo $post->pID?>" value="<?php echo $sw?>"/>
                        
                        <input type="hidden" name="pType" id="pType_<?php echo $post->pID?>" value="<?php echo $post->ptHandle?>"/>
            			
            
                        <button type="submit" name="submit" class="btn btn-success cws-post-update-btn" data-placement="bottom" title="<?php echo t('save')?>" id="status-post-edit_<?php echo $post->pID?>"><i class="icon-white icon-ok-circle"></i></button>
                        <button type="cancel" name="cancel" class="btn post-cancel" title="<?php echo t('cancel')?>" data-placement="bottom"><i class="icon-ban-circle"></i></button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                 </div>
                <div class="cws-comment-box"> 
									<?php 
                  $comments = $controller->getComments($post->pID, $post->ptHandle, $post->gbxID);
                  $postUserID = $controller->getPostUserID($post->pID);
                  Loader::packageElement('sb_comments','sharingbox', array('comments'=>$comments,'postUserID'=>$postUserID,'pID'=>$post->pID));
                  ?>
            		</div> 
            </div>
  
          <div class="clearfix"></div>       
          
        </li>
      </ul>
        
        <div class="modal hide" id="deletePostModal_<?php echo $post->pID?>" role="dialog" aria-labelledby="PostModal_<?php echo $post->pID?>" aria-hidden="true" data-backdrop="true">
          <div class="modal-header">
            <h3 id="CommentPost_<?php echo $post->pID?>"><?php echo t('Delete Post')?></h3>
          </div>
          <div class="modal-body">
			
            <p><?php echo t('Are you sure that you want to delete this post?<br/>This action will also delete any comments associated with this post.')?></p>
          </div>
          <div class="modal-footer">
          	<form id="cws-post-delete-form" method="post" action="">
          	
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo t('Cancel')?></button>
            <button id="deletePost_<?php echo $post->pID?>" class="btn btn-danger delete-post-btn"><?php echo t('Delete')?></button>
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
		endif;
	endforeach; 
endif;
?>
  
<script type="text/javascript">
$("#more-posts-loader").hide();
readyCommentStream();	
</script>

