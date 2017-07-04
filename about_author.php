<?php 
	$blurb = get_the_author_meta('user_description');
	$authorId = get_the_author_meta('ID');
	if ($blurb) :
		if(function_exists('get_cupp_meta')) $thumbnail = get_cupp_meta($authorId, 'medium')
?>
<div class="clearfix"></div>
<div class="panel">
	<div class="panel-heading">About The Author</div>
	<div class="panel-body">
		<?php if ($thumbnail) : ?>
			<img src="<?php echo $thumbnail;?>" alt="Author Photo" class="profile-image">
		<?php endif; ?>
		<?php echo $blurb ?>
	</div>
</div>
<?php endif; ?>