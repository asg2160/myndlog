<div id="sign_in_wrapper">
	<span class="close">X</span>
	<div id="sign_in_box" class="article">
		<?php if($error == 'sign_in_invalid'): ?>
			<span class="sign_in_error">Invalid Email or Password!</span>
		<?php endif; ?>
	
		<form id="sign_in" method="post" action="<?php echo getURL('SignIn',array('tag'=>$args['selectedTagName'])); ?>">
			<label for="email">Email</label>
			<input type="text" name="email" value="<?php echo $args['rmEmail']; ?>">
			<br>
			<label for="password">Password</label>
			<input type="password" name="password" value="<?php echo $args['rmPassword']; ?>">
			<br>
			<input type="checkbox" name="remember_me" checked="checked">
			<label name="remember_me" for="remember_me" class="remember_me">remember me on this device</label>
			<br>
			<input class="submit" type="submit" name="sign_in" value="Sign In">
		</form>
		
		<?php if($isMashup) { ?>
			<a class="sign_up">New User? Sign Up!</a>
		<?php } else { ?>
			<a class="sign_up" href="<?php echo getURL('Register',array('tag'=>$args['selectedTagName'])); ?>">New User? Sign Up!</a>
		<?php } ?>
	</div>
	
	<?php
		include_once('View/SignIn/Copy.php');
	?>
</div>