<?php
class HomeController extends Controller {
	
	function load($args) {
		
		$this->loadJS(array('Home','jquery.ui','jquery.slimscroll'));
		$this->loadCSS(array('jquery.ui'));
				
		if($args['post']['action']) {
			
			switch($args['post']['action']) {
			
				case 'delete':
					// DELETE THOUHTS NOT WORKING 
					Thought::delete($args['post']['tid']);
					ThoughtTag::deleteByThought($args['post']['tid']);
				break;
			
				case 'add':
					$thought = new Thought();
					$thoughtID = $thought->add($args['post']);
				break;
				
				case 'update':
				
					// MAKE A FUNCTION
					$thoughtData = array();
					if(!is_null($args['post']['title'])) $thoughtData["Title"] = $args['post']['title'];
					if(!is_null($args['post']['text'])) $thoughtData["Text"] = substr($args['post']['text'],0,1500);
					if(!is_null($args['post']['visible'])) $thoughtData["Visible"] = $args['post']['visible'];
					$thoughtID = $args['post']['tid'];
					
					Thought::updateByID($thoughtData,$thoughtID);
					
					if($thoughtID && !is_null($args['post']['tags'])) {
											
						$tagNames = explode(",",cleanupTags($args['post']['tags']));
					
						foreach($tagNames as $tagName) {
							$tag = new Tag();
							$tagID = $tag->add(array('name'=>$tagName));
							if(!$tagID) continue;
							$thoughtTag = new ThoughtTag();
							$thoughtTag->add(array('thoughtID'=>$thoughtID, 'tagID'=>$tagID));
						}
						
						$query = "DELETE FROM ThoughtTag WHERE ThoughtID = ".$thoughtID." AND TagID NOT IN (SELECT ID FROM Tag WHERE Name IN ".arrayToValueString($tagNames).");";	
						DB::query($query);
					}
				break;
				
				case 'get':
					$thought = new Thought($args['post']['tid']);
					echo json_encode(array("thoughtID"=>$args['post']['tid'],"title"=>$thought->title,"text"=>$thought->text,"tags"=>$thought->tags));
				break;
				
				case 'getxbeforey':
					$args['isPublicPage'] = $args['post']['isPublicPage'];
					$visible = $args['isPublicPage'] ? true : null;
					$userID = $args['post']['uid'] ? $args['post']['uid'] : $_SESSION['UserID'];
					if(!$userID) return false;
					$args['thoughtIDs'] = Thought::getXBeforeY($userID, $args['post']['num'], $args['post']['tid'], $args['post']['tag'],$visible);
					$this->standaloneView($args, 'View/Summary/Thoughts.php');
				break;
			}
			
		} else {
			if(!isAuth()) {
				loadURL($GLOBALS['DefaultPage']);
			}
			
			if($args['get']['keyword']) {
					$args['keyword'] = $args['get']['keyword'];
					$args['thoughtIDs'] = Thought::getByKeyword($args['keyword']);
					$args['message'] = "No thoughts found for keyword - '".$args['keyword']."'";
					$args['isSearch'] = true;
					
			} else {
						
				$args['selectedTagName'] = 'Show-All';
				$projectID = User::getDefaultProject($_SESSION['UserID']);
				
				if($args['get']['tag'] && $args['get']['tag']!='Show-All') {
					$args['selectedTagName'] = $args['get']['tag'];
					$args['thoughtIDs'] = Thought::getByUserAndTag($_SESSION['UserID'], $projectID, $args['get']['tag'], null, $GLOBALS['ThoughtsPerQuery']);
					if(!count($args['thoughtIDs'])) loadUrl('Home');
				} else {
					$args['thoughtIDs'] = Thought::getByUser($_SESSION['UserID'], $projectID, null, $GLOBALS['ThoughtsPerQuery']);
				}
				
				$args['tagNames'] = Tag::getAllNamesByUser($_SESSION['UserID']);
				$args['message'] = "You have not added any thoughts. Click on the 'Write' tab and get started.";
				$args['userID'] = $_SESSION['UserID'];
			}
			
			$this->view($args,'View/Home/Home.php');
		}
	}
}
?>