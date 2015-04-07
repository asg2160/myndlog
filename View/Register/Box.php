<div id="register_wrapper">
	<span class="close">X</span>
	<div id="register_box" class="article">
		<?php if($error == 'register_unique'): ?>
			<span class="register_error">Email's already taken!</span>
		<?php endif; ?>
		
		<?php if($isMashup) { ?>
		<a class="login">Login</a>
		<?php } else { ?>
			<a class="login" href="<?php echo getURL('SignIn',array('tag'=>$args['selectedTagName'])); ?>">Login</a>
		<?php } ?>
		
		<form id="register" method="post" enctype="multipart/form-data" action="<?php echo getURL('Register',array('tag'=>$args['selectedTagName'])); ?>">
			<label for="email">Email</label>
			<input type="text" name="email" id="email">
			
			<label for="password">Password</label>
			<input type="password" name="password" id="password">
			
			<label for="confirm_password">Confirm Password</label>
			<input type="password" name="confirm_password" id="confirm_password">
			
			<label for="user_name">User Name</label>
			<input type="text" name="user_name" id="user_name">
			
			<input type="hidden" name="submit" value="1">
			<input class="submit" type="submit" value="Register">
		</form>
	</div>
	
	<?php
		include_once('View/Register/Copy.php');
	?>
</div>