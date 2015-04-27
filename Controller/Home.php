<?php
class HomeController extends Controller {
	
	function load($args) {
		
		$this->loadJSInit(array('Home','jquery.ui'));
		$this->loadCSS(array('jquery.ui'));

		if($args['post']['action']) {
			switch($args['post']['action']) {
				
				case 'summary':
					$userID = $args['post']['tuid'];
					
					$pageName = $args['post']['page_name'];
					
					$user = new User($userID);
					
					$projectID = User::getDefaultProject($userID);
					
					$selectedTagName = $args['post']['selectedTagName'] ? $args['post']['selectedTagName'] : '';
					
					$thoughtIDLimit = $GLOBALS['ThoughtsPerQuery'];
										
					// If no tag is selected:
					if(!$selectedTagName) {
						
						// If it's the Page page
						if($pageName == 'Page') {
							$thoughtIDLimit = 10;
							$showLatestThoughtGroupedByTag = true;
						
						// For other pages (right now just 'Home')
						} else {
							$selectedTagName = 'Show-All';
						}
					}
										
					$isPublicPage = $args['post']['ipp'];
					
					$visible = $isPublicPage ? true : null;
					
					// TABS VIEW
					$viewArgs = array();
					$viewArgs['page_name'] = $pageName;
					$viewArgs['tagNames'] = Tag::getAllNamesByUser($userID,$visible);
					$viewArgs['selectedTagName'] = $selectedTagName;
					$viewArgs['thought_user_name'] = $user->userName;
					$viewArgs['thought_user_id'] = $user->ID;
					$tabView = $this->viewString($viewArgs, 'View/Summary/Tabs.php');
					$json_views['tabs'] = $tabView;
					
					// THOUGHTS VIEW		
					$viewArgs = array();
					$viewArgs['view_action'] = $args['post']['view_action'] ? $args['post']['view_action'] : 'replace';
					$viewArgs['page_name'] = $pageName;
					$viewArgs['thought_user_name'] = $user->userName;
					$viewArgs['selectedTagName'] = $selectedTagName;
					$viewArgs['isPublicPage'] = $isPublicPage;
					
					// all thoughts
					if($viewArgs['selectedTagName'] && $viewArgs['selectedTagName']!='Show-All') {
						$viewArgs['thoughtIDs'] = Thought::getByUserAndTag($userID, $projectID, $viewArgs['selectedTagName'], $visible, $thoughtIDLimit);
						$viewArgs['message'] = "You have not added any thoughts with tag '".$viewArgs['selectedTagName']."'";
					} else {
						$viewArgs['thoughtIDs'] = Thought::getByUser($userID, $projectID, $visible, $thoughtIDLimit);
						$viewArgs['message'] = "You have not added any thoughts.";
					}
					
					$thoughtIDsByTag = array();
					foreach($viewArgs['thoughtIDs'] as $thoughtID) {
						$thought = new Thought($thoughtID);
						$tags = ThoughtTag::getTags($thoughtID);
						foreach($tags as $tag) {
							if(!is_array($thoughtIDsByTag[$tag])) {
								$thoughtIDsByTag[$tag] = array();
							}
							
							$thoughtIDsByTag[$tag][] = $thoughtID;
						}
					}
					
					$viewArgs['thoughtIDsByTag'] = $thoughtIDsByTag;
					$viewArgs['thoughtIDsGroupedByTag'] = User::getThoughtsGroupedByTag($userID,true);
					$thoughtsView = $this->viewString($viewArgs, 'View/Summary/Thoughts.php');
					$json_views['thoughts'] = array($viewArgs['view_action']=>$thoughtsView);
					
					if($json_views) echo json_encode($json_views);
				break;
				
				case 'search':
					$userID = $args['post']['tuid'];
		
					// THOUGHTS VIEW
					$viewArgs = array();
					$viewArgs['view_action'] = $args['post']['view_action'] ? $args['post']['view_action'] : 'replace';
					$viewArgs['keyword'] = $args['get']['q'] ? $args['get']['q'] : $args['post']['q'];
					$viewArgs['thoughtIDs'] = Thought::getByUserAndKeyword($userID, $viewArgs['keyword']);
					$viewArgs['thought_user_name'] = $user->userName;
					$viewArgs['message'] = "No thoughts found with keyword - '".$viewArgs['keyword']."'";
					$thoughtsView = $this->viewString($viewArgs, 'View/Summary/Thoughts.php');
					$json_views['thoughts'] = array($viewArgs['view_action']=>$thoughtsView);
					
					if($json_views) echo json_encode($json_views);
				break;
				
				case 'delete':
					Thought::delete($args['post']['tid']);
					ThoughtTag::deleteByThought($args['post']['tid']);
					$user = new User($_SESSION['UserID']);
					
					$json_views = array();
					
					if($args['post']['paint_tabs']) {
						$viewArgs = array();
						$viewArgs['tagNames'] = Tag::getAllNamesByUser($user->ID);
						$viewArgs['thought_user_name'] = $user->userName;
						$viewArgs['thought_user_id'] = $user->ID;
						$viewArgs['selectedTagName'] = $args['post']['selectedTagName'];
						$tabView = $this->viewString($viewArgs, 'View/Summary/Tabs.php');
						$json_views['tabs'] = $tabView;
					}
					
					if($json_views) echo json_encode($json_views);
				break;
				
				case 'update':					
					$thought = new Thought($args['post']['tid']);
					$user = new User($_SESSION['UserID']);
					
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
						$viewArgs['tagNames'] = Tag::getAllNamesByUser($user->ID);
						$viewArgs['selectedTagName'] = $args['post']['selectedTagName'];
						$viewArgs['thought_user_name'] = $user->userName;
						$viewArgs['thought_user_id'] = $user->ID;
						$viewArgs['page_name'] = 'Home';
						$tabView = $this->viewString($viewArgs, 'View/Summary/Tabs.php');
						$json_views['tabs'] = $tabView;
					}
					
					if($args['post']['paint_new_thought']) {
						$viewArgs = array();
						$viewArgs['view_action'] = $args['post']['view_action'] ? $args['post']['view_action'] : 'replace';
						$viewArgs['thoughtIDs'] = array($thought->ID);
						$viewArgs['thought_user_name'] = $user->userName;
						$viewArgs['page_name'] = 'Home';
						$thoughtsView = $this->viewString($viewArgs, 'View/Summary/Thoughts.php');
						$json_views['thoughts'] = array($viewArgs['view_action']=>$thoughtsView,'thought_id'=>$thought->ID);
					}
					
					if($json_views) echo json_encode($json_views);
				break;
				
				case 'add':
					$user = new User($_SESSION['UserID']);
					
					$thought = new Thought();
					$thought->setValue('UserID',$user->ID);
					$thought->setValue('Text',$args['post']['text']);
					$thought->setValue('Title',$args['post']['title']);
					$thought->setValue('Visible',0);
					$thought->setValue('ProjectID',User::getDefaultProject($thought->getValue('UserID')));
					$thought->setValue('Tags',$args['post']['tags']);
					$thought->save();
					
					$json_views = array();
					
					if($args['post']['paint_tabs']) {
						$viewArgs = array();
						$viewArgs['tagNames'] = Tag::getAllNamesByUser($user->ID);
						$viewArgs['selectedTagName'] = $args['post']['selectedTagName'];
						$viewArgs['thought_user_name'] = $user->userName;
						$viewArgs['thought_user_id'] = $user->ID;
						$viewArgs['page_name'] = 'Home';
						$tabView = $this->viewString($viewArgs, 'View/Summary/Tabs.php');
						$json_views['tabs'] = $tabView;
					}
					
					if($args['post']['paint_new_thought']) {
						$viewArgs = array();
						$viewArgs['view_action'] = $args['post']['view_action'] ? $args['post']['view_action'] : 'replace';
						$viewArgs['thoughtIDs'] = array($thought->ID);
						$viewArgs['thought_user_name'] = $user->userName;
						$viewArgs['page_name'] = 'Home';
						$thoughtsView = $this->viewString($viewArgs, 'View/Summary/Thoughts.php');
						$json_views['thoughts'] = array($viewArgs['view_action']=>$thoughtsView);
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
					$user = new User($userID);
					$args['view_action'] = $args['post']['view_action'] ? $args['post']['view_action'] : 'replace';
					$args['thought_user_name'] = $user->userName;
					if(!$userID) return false;
					$args['thoughtIDs'] = Thought::getXBeforeY($userID, $args['post']['num'], $args['post']['tid'], $args['post']['tag'],$visible);
					$thoughtsView = $this->viewString($args, 'View/Summary/Thoughts.php');
					$json_views['thoughts'] = array($args['view_action']=>$thoughtsView, 'count'=>count($args['thoughtIDs']));
					echo json_encode($json_views);
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
				$args['selectedTagName'] = $args['get']['tag'];
				$args['userID'] = $_SESSION['UserID'];
			}
			
			$this->view($args,'View/Home/Home.php');
		}
	}
}
?>