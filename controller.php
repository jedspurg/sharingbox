<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

class SharingboxPackage extends Package {

	protected $pkgHandle = 'sharingbox';
	protected $appVersionRequired = '5.6';
	protected $pkgVersion = '1.3';
	
	public function getPackageDescription() {
		return t('Share statuses and links socially.');
	}
	
	public function getPackageName() {
		return t('SharingBox');
	}
	
	public function on_start() { 
		$pkt = Loader::helper('concrete/urls');
		$pkg = Package::getByHandle('sharingbox'); 
		Events::extend('on_user_friend_add', 'SbEvents', 'friend_add', __DIR__.'/libraries/sb_events.php');
		Events::extend('on_user_delete', 'SbEvents', 'user_delete', __DIR__.'/libraries/sb_events.php');
	}
	
	public function install() {
		$pkg = parent::install();

		//Install blocks
		$sbx = BlockType::getByHandle('sharingbox');
		if(!is_object($sbx)){
			BlockType::installBlockTypeFromPackage('sharingbox', $pkg);
		}
		
	}

	public function upgrade() {
		
		$pkg = Package::getByHandle('sharingbox');
		
		//Install blocks
		$sbx = BlockType::getByHandle('sharingbox');
		if(!is_object($sbx)){
			BlockType::installBlockTypeFromPackage('sharingbox', $pkg);
		}

		parent::upgrade();
	}
	
	public function uninstall(){
			
		$db = Loader::db();
		$db->Execute("DROP TABLE btSharingbox");
		$db->Execute("DROP TABLE SharingboxPostTemplates");
		$db->Execute("DROP TABLE SharingboxPosts");
		$db->Execute("DROP TABLE SharingboxComments");
		
		parent::uninstall();
	}
	
	
}

