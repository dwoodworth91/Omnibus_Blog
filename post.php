<span class='post-title'><?php the_title() ?></span>
<div class='post-meta'>
	<h4 class='whisper post-author'><?php the_author() ?></h4>
	<h4 class='whisper post-date'><?php the_date() ?></h4>
</div>
<hr/ class="title-content-separator">
<div class='post-content'>
	<?php echo apply_filters('the_content', get_the_content()); ?>
</div>