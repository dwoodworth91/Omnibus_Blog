<?php
	global $wp_query;
	$resultCount = $wp_query->found_posts;
	$shownCount = $wp_query->post_count;
	$is_search = $wp_query->is_search();
	$is_single = $wp_query->is_single();

	$COLUMN_COUNT = 4;
	$PREVIEW_LENGTH = 100;
	$totalCells = ceil($wp_query->post_count / $COLUMN_COUNT) * $COLUMN_COUNT
?>

<?php if ( have_posts() ) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php
			$index = $wp_query->current_post;
			$colIndex = $index % $COLUMN_COUNT;
			$isLastCol = ($colIndex == $COLUMN_COUNT -1) || ($index + 1 == $resultCount);
		?>
			<div class="card">
				<div class="card-featured-image-wrapper">
					<?php if ( $the_post_thumbnail = get_the_post_thumbnail()): ?>
						<?php echo $the_post_thumbnail; ?>
						<?php the_author_public_profile_img_with_classes('small', array('img-circle')); ?>
					<?php endif; ?>
				</div>
				<div class="card-text">
					<span class='post-title card-title'>
						<a href="<?php the_permalink() ?>">
							<?php the_title()?>
						</a>
					</span>
					<div class='post-meta'>
						<h4 class='whisper post-author'><?php the_author() ?></h4>
						<h4 class='whisper post-date'><?php echo get_the_date() ?></h4>
					</div>
					<div class="clearfix"></div>
					<?php echo apply_filters('the_content', wp_trim_words( get_the_content(), $PREVIEW_LENGTH, '...' )); ?>
				</div>
			</div>
	<?php endwhile;?>
<?php endif; ?>
