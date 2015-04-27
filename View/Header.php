<title>MyndLog</title>
<meta name="description" content="MyndLog lets you add, save and modify all your thoughts in one location. They can be writings, rough ideas, 2DO lists or anything random, say a dream you didn't forget"/>
<meta name="keywords" content="write,thoughts,blog,introspect,2do,todo,ideas"/>
<meta name="robots" content="index,follow"/>
<meta name=viewport content="width=device-width, initial-scale=1">
<main>
<?php 
	ob_start(); 
	if(isAuth()) $user = new User($_SESSION['UserID']);
?>
<div id="container">
	<div id="dialog-confirm">
	</div>
	<div id="header">
		<div class="contents">
			<input type="hidden" class="page_name" value="<?php echo $GLOBALS['Page']; ?>">
			<?php if(!in_array($GLOBALS['Page'],array('Page','Thought'))) { ?>
				<a class="nds" href="<?php echo getURL('Home');?>">
					<?php echo $GLOBALS['Name']; ?>
				</a>
				<div class="user_links">
					<?php if(isAuth()) { ?>
						<?php if( in_array($GLOBALS['Page'], array("Home","Notepad")) ): ?>
							<a id="toggle_write" class="data_action">Write</a>
						<?php endif; ?>
						
						<?php if($GLOBALS['Page'] != "Page"): ?>
							<a class="my_public_page" href="<?php echo getURL('Notepad'); ?>">Notes</a>
							<a class="my_public_page" href="<?php echo getURL('EditProfile'); ?>">Edit Profile</a>
							<a class="my_public_page" href="<?php echo $user->getPublicPageURL(); ?>">My Public Page</a>
						<?php endif; ?>
						
						<a class="sign_out" href="<?php echo getURL('SignIn',array('so'=>1));?>">Sign Out</a>
					<?php } ?>
				</div>
			<?php } else { ?>
				<?php $user = new User(User::getIDByUsername(urldecode($args['thought_user_name']))); ?>
				<div class="user_name">
					<a class="nds" href="<?php echo $user->getPublicPageURL();?>">
						<?php echo $user->userName; ?>
					</a>
				</div>
				
				<div class="user_links news_page">
					<?php if(isAuth() && $user->ID == $_SESSION['UserID']) { ?>
						<a href="<?php echo getURL('Home');?>">Home</a>
						<a href="<?php echo getURL('SignIn',array('so'=>1));?>">Sign Out</a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<div id="wrapper">
		<div id="home">