<div id="thought_log">
	<input id="thought_id" value="0" type="hidden">
	
	<span class="hide">x</span>
		
	<p>Title (Not mandatory <strong>but</strong> articles without a title cannot be made public.):</p>
	<input id="title" type="text">
	
	<p>Text:</p>
	<textarea id="text"></textarea>
	
	<br>
	
	<p>Enter a Tag:</p>
	<input id="tags" type="text" name="tags"><br>
		
	<br>
	
	<div class="keep_write_open">
		<span>automatically open this box on page load</span>
		<input type="checkbox" <?php if($_COOKIE['keepWriteOpen']) echo 'checked'; ?>>
	</div>
		 
	<input id="write" type="button" value="save">
</div>