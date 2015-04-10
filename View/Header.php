<title>MyndLog</title>
<meta name="description" content="MyndLog lets you add, save and modify all your thoughts in one location. They can be writings, rough ideas, 2DO lists or anything random, say a dream you didn't forget"/>
<meta name="keywords" content="write,thoughts,blog,introspect,2do,todo,ideas"/>
<meta name="robots" content="index,follow"/>
<?php
ob_start();

// not important right now. also, experiment with a different color picker.
// include_once "View/Colors.php";

/*
foreach (glob("Library/CSS/*.css") as $filename) {
	echo "<style>";
	include_once $filename;
	echo "</style>";
}
*/

if($GLOBALS['isDev']) {
	$cssFiles = array(
		"Library/CSS/Colors/Light.css",
		"Library/CSS/Notepad.css",
		"Library/CSS/Thought.css",
		"Library/CSS/Dialog.css",
		"Library/CSS/EditProfile.css",
		"Library/CSS/Footer.css",
		"Library/CSS/Header.css",
		"Library/CSS/Main.css",
		"Library/CSS/Page.css",
		"Library/CSS/SignIn_Register.css",
		"Library/CSS/jquery-ui.css",
		"Library/CSS/jquery.growl.css",
		"Library/CSS/Footer.css",
		"Library/CSS/Mashup.css"
	);
	
	foreach ($cssFiles as $filename) {
		echo "<style>";
		include_once $filename;
		echo "</style>";
	} 
} else {
	echo "<style>";
	include_once "Library/CSS/all.min.04.08.15.1.css";
	echo "</style>";
}
?>
<div id="container">
	<div id="dialog-confirm">
	</div>
<div id="header">

	<div class="contents">
	<?php if(!in_array($GLOBALS['Page'],array('Page','Thought'))) { ?>
		<a class="nds" href="<?php echo getURL('Home');?>">
			<?php echo $GLOBALS['Name']; ?>.
		</a>

		<div class="user_links">
			<?php if(isAuth()) { ?>
				<?php if( in_array($GLOBALS['Page'], array("Home","Notepad")) ): ?>
					<a id="toggle_write" class="data_action">Write</a>
				<?php endif; ?>
				
				<?php if($GLOBALS['Page'] != "Page"): ?>
					<a class="my_public_page" href="<?php echo getURL('Notepad'); ?>">Notes</a>
					<a class="my_public_page" href="<?php echo getURL('EditProfile'); ?>">Edit Profile</a>
					<?php $user = new User($_SESSION['UserID']); ?>
					<a class="my_public_page" href="<?php echo $user->getPublicPageURL(); ?>">My Public Page</a>
				<?php endif; ?>
				
				<a class="sign_out" href="<?php echo getURL('SignIn',array('so'=>1));?>">Sign Out</a>
				<?php } else { ?>
					<div id="sign_in_register">
						<?php if($GLOBALS['DefaultPage'] == 'Mashup'): ?>
							<?php if($GLOBALS['Page'] == 'Mashup'): ?>
								<a id="show_sign_in">Sign In</a>
								<a id="show_register">Register</a>
							<?php endif; ?>
	
							<?php if($GLOBALS['Page'] == 'SignIn'): ?>
								<a id="show_sign_in" class="disabled" disabled>Sign In</a>
								<a href="<?php echo getURL('Register'); ?>">Register</a>
							<?php endif; ?>
							
							<?php if($GLOBALS['Page'] == 'Register'): ?>
								<a href="<?php echo getURL('SignIn'); ?>">Sign In</a>
								<a id="show_register" class="disabled" disabled>Register</a>
							<?php endif; ?>
						<?php endif; ?>	
					</div>
			<?php } ?>
		</div>
	<?php } else { ?>
		<?php $user = new User(User::getIDByUsername(urldecode($args['thought_user_name']))); ?>
		<a class="nds" href="<?php echo $user->getPublicPageURL();?>">
			<?php echo $user->userName; ?>
		</a>
	
		<div class="powered_by">
			<a href="<?php echo getURL('Home');?>">
				powered by 
				<span class="unklog"><?php echo $GLOBALS['Name']; ?>.</span>
			</a>
		</div>
	<?php } ?>
</div>
</div>
<div id="wrapper">
<div id="home">