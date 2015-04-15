<?php
class HomeController extends Controller {
	
	function load($args) {
		
		$this->loadJSInit(array('Home','jquery.ui','jquery.slimscroll'));
		$this->loadCSS(array('jquery.ui'));
				
		if($args['post']['action']) {
			switch($args['post']['action']) {
			
				case 'delete':
					Thought::delete($args['post']['tid']);
					ThoughtTag::deleteByThought($args['post']['tid']);
					
					$json_views = array();
					
					if($args['post']['paint_tabs']) {
						$viewArgs = array();
						$viewArgs['tagNames'] = Tag::getAllNamesByUser($_SESSION['UserID']);
						$viewArgs['selectedTagName'] = $args['post']['selectedTagName'];
						$tabView = $this->viewString($viewArgs, 'View/Summary/Tabs.php');
						$json_views['tabs'] = $tabView;
					}
					
					if($json_views) echo json_encode($json_views);
				break;
				
				case 'update':
					$thought = new Thought($args['post']['tid']);
					
					if(array_key_exists('text',$args['post']))
						$thought->setValue('Text',$args['post']['text']);
					
					if(array_key_exists('title',$args['post']))
						$thought->setValue('Title',$args['post']['title']);
					
					if(array_key_exists('visible',$args['post']))
						$thought->setValue('Visible',$args['post']['visible']);
					
					if(array_key_exists('tags',$args['post']))
						$thought->setValue('Tags',$args['post']['tags']);
					
					$thought->save();
					
					if($args['post']['paint_tabs']) {
						$viewArgs = array();
						$viewArgs['tagNames'] = Tag::getAllNamesByUser($_SESSION['UserID']);
						$viewArgs['selectedTagName'] = $args['post']['selectedTagName'];
						$tabView = $this->viewString($viewArgs, 'View/Summary/Tabs.php');
						$json_views['tabs'] = $tabView;
					}
					
					if($args['post']['paint_new_thought']) {
						$viewArgs = array();
						$viewArgs['thoughtIDs'] = array(array('ID'=>$thought->ID));
						$thoughtView = $this->viewString($viewArgs, 'View/Summary/Thoughts.php');
						$json_views['new_thought'] = $thoughtView;
					}
					
					if($json_views) echo json_encode($json_views);
				break;
				
				case 'add':
					$thought = new Thought();
					$thought->setValue('UserID',$_SESSION['UserID']);
					$thought->setValue('Text',$args['post']['text']);
					$thought->setValue('Title',$args['post']['title']);
					$thought->setValue('Visible',0);
					$thought->setValue('ProjectID',User::getDefaultProject($thought->getValue('UserID')));
					$thought->setValue('Tags',$args['post']['tags']);
					$thought->save();
					
					$json_views = array();
					
					if($args['post']['paint_tabs']) {
						$viewArgs = array();
						$viewArgs['tagNames'] = Tag::getAllNamesByUser($_SESSION['UserID']);
						$viewArgs['selectedTagName'] = $args['post']['selectedTagName'];
						$tabView = $this->viewString($viewArgs, 'View/Summary/Tabs.php');
						$json_views['tabs'] = $tabView;
					}
					
					if($args['post']['paint_new_thought']) {
						$viewArgs = array();
						$viewArgs['thoughtIDs'] = array(array('ID'=>$thought->ID));
						$thoughtView = $this->viewString($viewArgs, 'View/Summary/Thoughts.php');
						$json_views['new_thought'] = $thoughtView;
					}
					
					if($json_views) echo json_encode($json_views);
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
					$thoughtsView = $this->viewString($args, 'View/Summary/Thoughts.php');
					echo json_encode(array('thoughts_view'=>$thoughtsView));
				break;
			}
			
		} else {
			if(!isAuth()) {
				loadURL($GLOBALS['DefaultPage']);
			}
			
			if($args['get']['keyword']) {
					$args['keyword'] = $args['get']['keyword'];
					$args['thoughtIDs'] = Thought::getByUserAndKeyword($_SESSION['UserID'], $args['keyword']);
					$args['message'] = "No thoughts found with keyword - '".$args['keyword']."'";
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