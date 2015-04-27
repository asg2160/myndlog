<div id="articles">
	<form id="edit_profile" class="article" method="post" enctype="multipart/form-data" action="<?php echo getURL('EditProfile'); ?>">
		
		<!-- till system can send emails like forgot password, verification..ignore this. 
		<label for="email">Email</label>
		<input type="text" name="email" id="email">
		 -->
		
		<label for="user_name">User Name</label><span class="edit_me">&#9997;</span>
		<input type="text" name="user_name" id="user_name" value="<?php echo $args['user_user_name']; ?>"  disabled>
						
		<label for="new_password">Password</label><span class="edit_me">&#9997;</span>
		<input type="password" name="new_password" id="new_password" disabled>
		
		<label for="confirm_new_password" class="confirm_new_password">Confirm New Password</label>
		<input type="password" name="confirm_new_password" id="confirm_new_password" class="confirm_new_password" disabled>
		
		<input class="submit" type="submit" name="edit" value="Save">
	</form>

	<div class="article select_themes">
		<label for="themes">
			<span>CHANGE THEME</span>
		</label>
		
		<select id="themes">
		<?php
			foreach($args['themes'] as $id=>$theme) {
				$themeID = $id + 1;
				$selected = ($themeID == $args['user_theme_id']) ? 'selected' : '';
				echo "<option value='".$themeID."' ".$selected.">".$theme."</option>";
			}
		?>
		</select>
		
		<span>Check your <a href='<?php echo $args['user_public_page_url']; ?>'>public page</a> after changing the theme</span>
	</div>
</div>