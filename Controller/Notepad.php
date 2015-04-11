<?php
class NotepadController extends Controller {
	function load($args) {
		
		$this->loadJS(array('Notepad','Home','jquery.ui','jquery.slimscroll'));
		$this->loadCSS(array('Notepad','jquery.ui'));
		
		if(!isAuth()) loadUrl('Home');
		
		$notepadID = Notepad::getIDByUserID($_SESSION['UserID']);
		$notepad = new Notepad($notepadID);
		$args['notes'] = $notepad->notes;
		
		if($args['post']['action']) {
			switch($args['post']['action']) {
				case 'update':
					Notepad::updateByID($args['post'], $notepadID);
				break;
			}
		}
		
		$this->view($args,'View/Notepad/Notepad.php');
	}
}
?>