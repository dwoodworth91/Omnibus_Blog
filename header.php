<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
		<title><?php bloginfo('name'); ?></title>
		<?php wp_head(); ?>
	</head>
	<body class="<?php echo is_singular() ? '' : 'cards-background' ?>">
		<div class="page-row page-row-expanded">
			<div class="container-fluid" id="banner-background">
				<div class="row">
					<div class="col-lg-1 col-md-0"></div>
					<div id="banner" class="col-md-12 col-lg-10">
						<div class="banner-logo-container">
							<?php get_template_part( 'logo' ); ?>
							<h4><?php echo get_option('blogdescription'); ?></h4>
						</div>
					</div>
					<div class="col-lg-1 col-md-0"></div>
				</div>
			</div>
			<?php get_sidebar(); ?>
