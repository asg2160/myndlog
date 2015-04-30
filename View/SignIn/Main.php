<div id="sign_in_page">	
	<p class="heading">Log, maintain and share your thoughts.</p>
		
	<div id="sign_in_box">
		<p class="links">
			<a class="sign_in">SignIn</a>
			<a class="register">Register</a>
		</p>
		
		<form id="sign_in" method="post" action="<?php echo getURL('SignIn',array('tag'=>$args['selectedTagName'])); ?>">
			<label for="email">Email</label>
			<input type="text" name="email" value="<?php echo $args['rmEmail']; ?>">
			
			<label for="password">Password</label>
			<input type="password" name="password" value="<?php echo $args['rmPassword']; ?>">
			
			<input type="checkbox" name="remember_me" checked="checked">
			<label name="remember_me" for="remember_me" class="remember_me">remember me on this device</label>
			
			<input class="submit" type="submit" name="sign_in" value="Sign In">
		</form>
	
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
	
	<?php //include_once('View/SignIn/Copy.php'); ?>
</div>