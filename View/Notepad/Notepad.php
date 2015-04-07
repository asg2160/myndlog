<div id="notepad">
	<span>Rough Work : If you don't want to add individual thoughts, then write and save all of them in this notepad. When you have something to add, click on 'Write' and copy it into the write box.</span>
	<textarea><?php echo $args['notes']; ?></textarea>
	<input type="submit" value="save" id="save_notepad">
</div>
<?php	
	include_once('View/Common/WriteBox.php');
?>