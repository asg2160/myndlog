<div id="notepad">
	<span>Rough Work : Doodle and save your work. When you have thoughts to add, click on 'Write' and copy your work into the write box.</span>
	<span class="saved_text">your work was saved at 
		<span class="time"></span>
	</span>
	<textarea><?php echo $args['notes']; ?></textarea>
	<input type="submit" value="save" id="save_notepad">
</div>
<?php	
	include_once('View/Common/WriteBox.php');
?>