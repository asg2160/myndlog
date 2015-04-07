<div id="articles">
	<form id="edit_profile" class="article" method="post" enctype="multipart/form-data" action="<?php echo getURL('EditProfile'); ?>">
		
		<!-- till system can send emails like forgot password, verification..ignore this. 
		<label for="email">Email</label>
		<input type="text" name="email" id="email">
		 -->
		
		<label for="user_name">User Name</label><span class="edit_me">&#9997;</span>
		<input type="text" name="user_name" id="user_name" value="<?php echo $args['user']->userName; ?>"  disabled>
						
		<label for="new_password">Password</label><span class="edit_me">&#9997;</span>
		<input type="password" name="new_password" id="new_password" disabled>
		
		<label for="confirm_new_password" class="confirm_new_password">Confirm New Password</label>
		<input type="password" name="confirm_new_password" id="confirm_new_password" class="confirm_new_password" disabled>
		
		<input class="submit" type="submit" name="edit" value="Save">
	</form>
</div>