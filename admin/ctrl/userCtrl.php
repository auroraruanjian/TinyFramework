<?php
namespace admin\ctrl;

class userCtrl extends \core\lib\baseCtrl  {
	
	/**
	 * 
	 */
	public function actionLoginout(){
		session_destroy();
		redirect('/admin'.url('default','login'));
	}
	
}