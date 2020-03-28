<?php
	const TILES_ACTION_NAME = 'get_tiles';
	const ARCHIVE_ACTION_NAME = 'get_archive';
	const HTML_CONTENT_TYPE = 'text/html';
	const JSON_CONTENT_TYPE = 'text/json';

	/*JS Registrars*/
	function prefix($name) {
		return "ombc_$name";
	}

	function add_front_end_JS(){
		wp_register_script(prefix('jQuery'), get_template_directory_uri().'/libs/jQuery-2.1.4/jquery-2.1.4.min.js',false);
		wp_enqueue_script(prefix('jQuery'));

		wp_register_script(prefix('Bootstrap'), get_template_directory_uri().'/libs/bootstrap-3.3.5-dist/js/bootstrap.min.js',false);
		wp_enqueue_script(prefix('Bootstrap'));

		wp_register_script(prefix('scripts'), get_template_directory_uri().'/scripts.js',false);
		wp_enqueue_script(prefix('scripts'));

		$model = $base_ajax_model = array(
			'admin_ajax_url' => admin_url('admin-ajax.php'),
			'archive_action' => ARCHIVE_ACTION_NAME
		);

		if(is_home() || is_search() || is_category()){
			global $wp_query;
			global $query_string;

			$model = array_merge($base_ajax_model, array(
				'infinite_tiles_action' => TILES_ACTION_NAME,
				'pageNumber' => '1',
				'pageCount' => $wp_query->max_num_pages,
				'postsPerPage' => get_option('posts_per_page'),
				'queryString' => $query_string
			));

			/*Libs not automatically enqueued*/
			wp_register_script(prefix('lodash'), get_template_directory_uri().'/libs/lodash-4.17.4/lodash.js',false);
			wp_register_script(prefix('pubsub'), get_template_directory_uri().'/libs/jquery-tiny-pubsub-0.7.0/ba-tiny-pubsub.min.js',false);
			wp_localize_script(prefix('pubsub'), 'events', array(
				'CARDS_LOADED' => 'cards/loaded',
				'CARDS_LOADED_IMAGES' => 'cards/loaded/images'
			));
			wp_register_script(prefix('images_loaded'), get_template_directory_uri().'/libs/images-loaded-4.1.3/imagesloaded.pkgd.js',false);
			wp_register_script(prefix('lib_masonry'), get_template_directory_uri().'/libs/masonry-4.2.0/masonry.pkgd.min.js',false);

			/*Theme scripts*/
			wp_register_script(prefix('infinite_scrolling'), get_template_directory_uri().'/infinite_scrolling.js', array(prefix('images_loaded'), prefix('pubsub')));
			wp_enqueue_script(prefix('infinite_scrolling'));

			wp_register_script(prefix('ombc_masonry'), get_template_directory_uri().'/masonry.js', array(prefix('lib_masonry'), prefix('lodash'), prefix('pubsub')));
			wp_enqueue_script(prefix('ombc_masonry'));
		}

		wp_localize_script(prefix('scripts'), 'model', $model );
	}
	add_action('wp_enqueue_scripts', 'add_front_end_JS');

	/*CSS*/
	function add_front_end_CSS(){
		wp_register_style(prefix('google-fonts'), 'https://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300', false);
		wp_enqueue_style(prefix('google-fonts'));

		wp_register_style(prefix('main-style'), get_template_directory_uri().'/style.css', false);
		wp_enqueue_style(prefix('main-style'));

		wp_register_style(prefix('sticky-footer-style'), get_template_directory_uri().'/fluid-height-sticky-footer.css', false);
		wp_enqueue_style(prefix('sticky-footer-style'));

		wp_register_style(prefix('bootstrap-custom-theme'), get_template_directory_uri().'/bootstrap-custom-theme.css', false);
		wp_enqueue_style(prefix('bootstrap-custom-theme'));

		wp_register_style(prefix('bootstrap-min'), get_template_directory_uri().'/libs/bootstrap-3.3.5-dist/css/bootstrap.min.css', false);
		wp_enqueue_style(prefix('bootstrap-min'));

		wp_register_style(prefix('bootstrap-theme-min'), get_template_directory_uri().'/libs/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css', false);
		wp_enqueue_style(prefix('bootstrap-theme-min'));

		wp_register_style(prefix('logo-style'), get_template_directory_uri().'/logo.css', false);
		wp_enqueue_style(prefix('logo-style'));

		wp_register_style(prefix('loading-bars'), get_template_directory_uri().'/loading.css', false);
		wp_enqueue_style(prefix('loading-bars'));

		wp_register_style(prefix('tile-hover'), get_template_directory_uri().'/hover.css', false);
		wp_enqueue_style(prefix('tile-hover'));
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
		/*Combine request params and with additional params*/
		$queryParams = array_merge($_POST, array(
			'posts_per_page' => get_option('posts_per_page')
		));
		ajaxQuery('loop_tiles', $queryParams, JSON_CONTENT_TYPE);
	}
	add_action('wp_ajax_' . TILES_ACTION_NAME, 'infinite_tiles_action');
	add_action('wp_ajax_nopriv_' . TILES_ACTION_NAME, 'infinite_tiles_action');

	/*Lazy-load archive*/
	function archive_action(){
		ajaxQuery('archive', array('nopaging' => true), HTML_CONTENT_TYPE);
	}
	add_action('wp_ajax_' . ARCHIVE_ACTION_NAME, 'archive_action');
	add_action('wp_ajax_nopriv_' . ARCHIVE_ACTION_NAME, 'archive_action');

	function ajaxQuery($templatePart, $queryParams, $contentType){
		header("Content-Type: $contentType");
		query_posts(array_merge($queryParams, array('post_status' => 'publish')));
		ob_start();
		get_template_part($templatePart, get_post_format());
		$content = ob_get_contents();
		ob_end_clean();
		wp_reset_query();
		echo (JSON_CONTENT_TYPE == $contentType ? json_encode($content) : $content);
		exit;
	}

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
