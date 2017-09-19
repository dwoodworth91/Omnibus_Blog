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
				<?php if ( has_post_thumbnail() ): ?>
				<?php the_post_thumbnail(); ?>
				<?php endif; ?>
				<div class="card-text">
					<!-- <?php the_permalink() ?> -->
					<h4>
						<h2 class="card-title"><a href="<?php the_permalink() ?>"><?php the_title()?></a></h2>
					</h4>
					<div class='post-meta'>
						<h4 class='whisper post-author'><?php the_author() ?></h4>
						<h4 class='whisper post-date'><?php the_date() ?></h4>
					</div>
					<?php echo apply_filters('the_content', wp_trim_words( get_the_content(), $PREVIEW_LENGTH, '...' )); ?>
				</div>
			</div>
	<?php endwhile;?>
<?php endif; ?>
