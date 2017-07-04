<?php get_header(); ?>
	<div class="container">
		<div class="row">
			<div class="container-fluid" id="posts">
				<?php get_template_part( 'loop_singular' ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>