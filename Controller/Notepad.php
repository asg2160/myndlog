<?php
class NotepadController extends Controller {
	function load($args) {
		
		$this->loadJSInit(array('Notepad','Home','jquery.ui'));
		$this->loadCSS(array('Notepad','jquery.ui'));
		
		if(!isAuth()) loadUrl('Home');
		
		$notepadID = Notepad::getIDByUserID($_SESSION['UserID']);
		$notepad = new Notepad($notepadID);
		
		if($args['post']['action']) {
			switch($args['post']['action']) {
				case 'update':
					$notepad->setValue('Notes',$args['post']['notes']);
					$notepad->save();
				break;
			}
			
			die();
		}
		
		$args['notes'] = $notepad->notes;
		$this->view($args,'View/Notepad/Notepad.php');
	}
}
?>