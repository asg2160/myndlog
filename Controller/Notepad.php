<?php
class NotepadController extends Controller {
	function load($args) {
		
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