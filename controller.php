<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

class SharingboxPackage extends Package {

	protected $pkgHandle = 'sharingbox';
	protected $appVersionRequired = '5.6';
	protected $pkgVersion = '1.0';
	
	public function getPackageDescription() {
		return t('Share statuses and links socially.');
	}
	
	public function getPackageName() {
		return t('SharingBox');
	}
	
	public function on_start() { 
		$pkt = Loader::helper('concrete/urls');
		$pkg= Package::getByHandle('sharingbox'); 
		Events::extend('on_user_friend_add', 'SbEvents', 'friend_add', __DIR__.'/libraries/sb_events.php');
	}
	
	public function install() {
		$pkg = parent::install();
		BlockType::installBlockTypeFromPackage('sharingbox', $pkg);
	}
	
	public function uninstall() {
		parent::uninstall();
    }	
	
	public function upgrade() {
		parent::upgrade();

	}
	
}

