<?php
	const TILES_ACTION_NAME = 'get_tiles';
	const ARCHIVE_ACTION_NAME = 'get_archive';

	/*JS Registrars*/
	function add_front_end_JS(){
		wp_register_script('jQuery', get_template_directory_uri().'/libs/jQuery-2.1.4/jquery-2.1.4.min.js',false);
		wp_enqueue_script('jQuery');

		wp_register_script('Bootstrap', get_template_directory_uri().'/libs/bootstrap-3.3.5-dist/js/bootstrap.min.js',false);
		wp_enqueue_script('Bootstrap');

		wp_register_script('scripts', get_template_directory_uri().'/scripts.js',false);
		wp_enqueue_script('scripts');

		$model = $base_ajax_model = array(
			'admin_ajax_url' => admin_url('admin-ajax.php'),
			'archive_action' => ARCHIVE_ACTION_NAME
		);

		if(is_home() || is_search() || is_category()){
			global $wp_query;
			global $query_string;

			wp_register_script('infinite_scrolling', get_template_directory_uri().'/infinite_scrolling.js',false);
			wp_enqueue_script('infinite_scrolling');

			$model = array_merge($base_ajax_model, array(
				'infinite_tiles_action' => TILES_ACTION_NAME,
				'pageNumber' => '1',
				'pageCount' => $wp_query->max_num_pages,
				'postsPerPage' => get_option('posts_per_page'),
				'queryString' => $query_string
			));
		}

		wp_localize_script( 'scripts', 'model', $model );
	}
	add_action('wp_enqueue_scripts', 'add_front_end_JS');

	/*CSS*/
	function add_front_end_CSS(){
		wp_register_style('google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300', false);
		wp_enqueue_style('google-fonts');

		wp_register_style('main-style', get_template_directory_uri().'/style.css', false);
		wp_enqueue_style('main-style');

		wp_register_style('sticky-footer-style', get_template_directory_uri().'/fluid-height-sticky-footer.css', false);
		wp_enqueue_style('sticky-footer-style');

		wp_register_style('bootstrap-custom-theme', get_template_directory_uri().'/bootstrap-custom-theme.css', false);
		wp_enqueue_style('bootstrap-custom-theme');

		wp_register_style('bootstrap-min', get_template_directory_uri().'/libs/bootstrap-3.3.5-dist/css/bootstrap.min.css', false);
		wp_enqueue_style('bootstrap-min');

		wp_register_style('bootstrap-theme-min', get_template_directory_uri().'/libs/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css', false);
		wp_enqueue_style('bootstrap-theme-min');

		wp_register_style('logo-style', get_template_directory_uri().'/logo.css', false);
		wp_enqueue_style('logo-style');

		wp_register_style('loading-bars', get_template_directory_uri().'/loading.css', false);
		wp_enqueue_style('loading-bars');

		wp_register_style('tile-hover', get_template_directory_uri().'/hover.css', false);
		wp_enqueue_style('tile-hover');

		wp_register_style('flip', get_template_directory_uri().'/flip.css', false);
		wp_enqueue_style('flip');
	}
	add_action('wp_enqueue_scripts', 'add_front_end_CSS');

	/*Menus*/
	function register_menus() {
	  register_nav_menus(
		array(
		  'main-menu' => __( 'Main Menu' ),
		  'main-menu-privileged' => __( 'Main Menu Privileged' ),
		  'footer-menu' => __( 'Footer Menu' )
		)
	  );
	}
	add_action('init', 'register_menus');

	/*Infinite Scrolling*/
	function infinite_tiles_action(){
		header('Content-Type: text/json');

		/*Combine request params and with additional params*/
		$queryParams = array_merge($_POST, array(
			'posts_per_page' => get_option('posts_per_page'),
			'post_status' => 'publish'
		));

		/*Query for posts*/
		query_posts($queryParams);

		/*Capture output as string*/
		ob_start();
		get_template_part('loop_tiles');
		$markup = ob_get_contents();
		ob_end_clean();

		echo json_encode($markup);

		exit;
	}
	add_action('wp_ajax_' . TILES_ACTION_NAME, 'infinite_tiles_action');
	add_action('wp_ajax_nopriv_' . TILES_ACTION_NAME, 'infinite_tiles_action');

	/*Lazy-load archive*/
	function archive_action(){
		header('Content-Type: text/html');

		/*Capture output as string*/
		ob_start();
		get_template_part( 'archive', get_post_format());
		$markup = ob_get_contents();
		ob_end_clean();

		echo $markup;

		exit;
	}
	add_action('wp_ajax_' . ARCHIVE_ACTION_NAME, 'archive_action');
	add_action('wp_ajax_nopriv_' . ARCHIVE_ACTION_NAME, 'archive_action');

	/*Drop case*/
	function addDropCase($content){
		$pattern = '/>(&#8220;)?(\w){1}/i';
		return preg_replace_callback($pattern,
			function ($matches) {
				return '>'.($matches[2] ? '<div class="pull-left">'.$matches[1].'</div>' : '').'<span class="drop-case">'.($matches[2] ? $matches[2] : $matches[1]).'</span>';
			},
			$content, 1
		);
		//return preg_replace($pattern, $replacement, $content, 1);
		//return $content;
	}
	add_filter('the_content','addDropCase');

	/*Utils*/
	function the_author_public_profile_img($size){
		the_author_public_profile_img_with_classes($size, array());
	}

	function the_author_public_profile_img_with_classes($size, $classes){
		if(function_exists('get_cupp_meta') && $thumbnail = get_cupp_meta(get_the_author_meta('ID'), $size)){
			echo "<img src='" . $thumbnail . "' alt='Author Photo' class='profile-image " . join(' ', $classes) . "'>";
		}
	}

	/*Theme Options*/
	add_theme_support( 'post-thumbnails' );
?>
