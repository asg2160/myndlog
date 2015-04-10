<div id="home_page">
	<form id="search" action="<?php echo getURL('Home'); ?>">
		<input type="text" class="box" name="keyword" value="<?php echo $args['keyword']; ?>">
		<input type="submit" value="search" class="button">
		<?php if($args['isSearch']): ?> 
			<a class="clear_search" href="<?php echo getURL('Home');?>">
				clear search
			</a>
		<?php endif; ?>
	</form>
	<?php
	$isHome = true;
	include_once('View/Summary/Summary.php');
	include_once('View/Common/WriteBox.php');
	?>
</div>