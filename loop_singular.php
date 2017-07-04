<?php
	global $wp_query;

	if ( have_posts() ) : 
		while (have_posts()) : the_post();
			get_template_part( 'post', get_post_format());
			get_template_part( 'about_author', get_post_format());
		endwhile;
		if ($wp_query->is_single() && comments_open()) comments_template();
	endif; 
?>