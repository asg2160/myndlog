<?php
$isPublicPage = $isArticle || $isPage || $isSignIn || $isRegister || $isMashup || $args['isPublicPage'];

if($isSignIn || $isMashup) {
	include_once('View/SignIn/Box.php');
}

if($isRegister || $isMashup) {
	include_once('View/Register/Box.php');
}

for($i=0; $i<count($args['thoughtIDs']); $i++) {
	
	$thought = new Thought($args['thoughtIDs'][$i]['ID']);
	
	$tags = str_replace(" ","-",$thought->tags);		

	$append = $i ? "," : ""; 
	$allTags .= $append.$tags;
	
	$classTags = str_replace(","," ",$tags);
	$monthClass = strtolower(date("F",$thought->dateAdded));
	$isVisible = $thought->visible;	
?>
	<div tags="<?php echo $classTags;?>" month="<?php echo $monthClass;?>" class="article thought" id=<?php echo "thought_".$thought->ID; ?>>
		<?php if(!$isPublicPage): ?>
			<p class="edit_thought data_action">
				edit
			</p>
		<?php endif; ?>
		
		<?php if($isPage): ?>
			<a href="<?php echo getUrl('Article',array('name'=>$args['get']['name'],'tid'=>$thought->ID)); ?>" class="open">
				open
			</a>
		<?php endif; ?>
		
		<p class="article_date">
			<?php echo date("F j, Y",$thought->dateAdded); ?>
		</p>
			
		<div class="text_container">
			<?php if($thought->title): ?>
				<p class="title"><?php echo $thought->title;?></p>
			<?php endif; ?>
			<p><?php echo nl2br($thought->text);?></p>
		</div>
		
		<p class="para_tags">
			<?php if(!$isPublicPage): ?><span>tags:</span><?php endif; ?>
			<span class="list"><?php echo str_replace(",",", ",strtoupper($thought->tags)); ?></span>
		</p>

		<?php if(!$isPublicPage): ?>
			<p class="delete_thought data_action" tid=<?php echo $thought->ID?>>x</p>
		<?php endif; ?>
		
		<div class="bottom">
			<?php if(!$isPublicPage): ?>
				make public<input type="checkbox" <?php echo ($isVisible ? 'checked' : '');?> class="visibility">
			<?php endif; ?>
		</div>
	</div>
<?php } ?>
