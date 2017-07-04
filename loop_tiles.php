<?php 
	global $wp_query;
	$resultCount = $wp_query->found_posts;
	$shownCount = $wp_query->post_count;
	$is_search = $wp_query->is_search();
	$is_single = $wp_query->is_single();
	
	$COLUMN_COUNT = 4;
	$PREVIEW_LENGTH = 500;
	$totalCells = ceil($wp_query->post_count / $COLUMN_COUNT) * $COLUMN_COUNT
?>

<?php if ( have_posts() ) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php 
			$index = $wp_query->current_post;
			$colIndex = $index % $COLUMN_COUNT;
			$isLastCol = ($colIndex == $COLUMN_COUNT -1) || ($index + 1 == $resultCount);
		?>
		<?php if( $colIndex == 0 ): ?><div class="row"><?php endif; ?>
			<div class="col-md-3 col-sm-6">
				<a class="post-tile-link" href="<?php the_permalink() ?>">
					<div class="panel post-tile">
						<div class="panel-heading"><div class="hvr-grow"><?php the_title()?></div></div>
						<div class="flip-container">
							<div class="flipper">
								<div class="front">
									<div class="panel-body post-tile-body pull-left" id="front-<?php the_ID(); ?>">
										<?php if ( has_post_thumbnail() ): ?>
											<?php the_post_thumbnail(); ?>
										<?php endif; ?>
										<?php //echo apply_filters('the_content', wp_trim_words( get_the_content(), $PREVIEW_LENGTH, '...' )); ?>
									</div>
								</div>
								<div class="back">
									<div class="panel-body post-tile-body pull-left" id="back-<?php the_ID(); ?>">
										<?php echo apply_filters('the_content', wp_trim_words( get_the_content(), $PREVIEW_LENGTH, '...' )); ?>
									</div>
								</div>
								<div class="spacer">
									<div class="panel-body post-tile-body pull-left" id="spacer-<?php the_ID(); ?>">
										<?php //echo apply_filters('the_content', wp_trim_words( get_the_content(), $PREVIEW_LENGTH, '...' )); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="post-tile-footer whisper"><?php echo get_the_date(); ?></div>
					</div>
				</a>
			</div>
		<?php if($isLastCol): ?></div><?php endif; ?>
	<?php endwhile;?>
<?php endif; ?>