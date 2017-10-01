<?php if ($blurb = get_the_author_meta('user_description')) : ?>
	<div class="clearfix"></div>
	<div class="panel author-about">
		<div class="panel-heading">About The Author</div>
		<div class="panel-body">
			<?php the_author_public_profile_img('medium'); ?>
			<?php echo $blurb ?>
		</div>
	</div>
<?php endif; ?>
