<div id="thought_log">
	<input id="thought_id" value="0" type="hidden">
	
	<span class="hide">x</span>
	
	<p>Write :</p>
	<input id="title" type="text">
	<textarea id="text" cols="55"></textarea>
	
	<br>
	
	<input id="tags" type="text" name="tags"><br>
	<p class="tags_desc">(Enter a single tag or multiple comma seperated tags. Eg: "philosphy" or "idea,book" or "sexism,economy,politics")</p>
	
	<br>
	
	<div class="keep_write_open">
		<span>automatically open this box on page load</span>
		<input type="checkbox" <?php if($_COOKIE['keepWriteOpen']) echo 'checked'; ?>>
	</div>
		 
	<input id="write" type="button" value="save">
</div>