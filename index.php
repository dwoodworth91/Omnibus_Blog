<?php
	global $wp_query;
	$is_search = $wp_query->is_search();
?>

<?php get_header(); ?>
			<div class="cards-background container-fluid">
				<div class="row">
					<div class="col-lg-1 col-md-0"></div>
					<div class="col-md-12 col-lg-10">
						<?php if ( $is_search ) : ?>
							<?php if ( have_posts() ) : ?>
								<div class="alert-message panel panel-default" role="alert">
									<div class="panel-body">
										<strong>Search:</strong>
										Showing <span class='badge'><?php echo $resultCount; ?></span> results for <strong><em>&quot<?php echo get_search_query(); ?>&quot</em></strong>
									</div>
								</div>
							<?php else : ?>
								<div class="alert-message panel panel-default" role="alert">
									<div class="panel-body">
										<strong>Search:</strong>
										No Results Found For
										<strong><em>&quot<?php echo get_search_query(); ?>&quot</em></strong>
									</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						<div class="cards" id="posts">

						</div>
						<?php get_template_part( 'loading_posts' ); ?>
						<?php get_template_part( 'no_more_posts' ); ?>
					</div>
					<div class="col-lg-1 col-md-0"></div>
				</div>
			</div>
		</div>
	</body>
	<?php wp_footer(); ?>
</html>
