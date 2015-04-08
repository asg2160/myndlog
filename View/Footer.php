		</div>
	</div>
	<div id="footer">
		<p class="contact">MyndLog &copy; 2015 | contact@myndlog.com</p>
	</div>
</div>
<?php
if($GLOBALS['isDev']&&0) {
	
	$jsFiles = array(
		"Library/JS/jquery.min.js",
		"Library/JS/Main.js",
		"Library/JS/jquery-cookie.js",
		"Library/JS/EditProfile.js",
		"Library/JS/Home.js",
		"Library/JS/jquery-ui.min.js",
		"Library/JS/Register.js",
		"Library/JS/SignIn.js",
		"Library/JS/jquery-slimscroll.js",
		"Library/JS/jquery-validate.js",
		"Library/JS/Notepad.js"
	);
	
	foreach($jsFiles as $filename) {
		echo "<script type='text/javascript'>";
		include_once $filename;
		echo "</script>";
	}

} else {
	echo "<script type='text/javascript'>";
	include_once "Library/JS/all.min.04.08.15.js";
	echo "</script>";
}
?>
<?php 
ob_end_flush();
?>