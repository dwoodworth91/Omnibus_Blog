<nav class="navbar" id="main-menu">
 <div class="container">
	<div class="navbar-header">
	  <?php get_template_part( 'logo' ); ?>
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	</div>

	<div class="collapse navbar-collapse" id="navbar-collapse">
		<a class="navbar-brand" href="<?php bloginfo('home'); ?>"><?php get_template_part( 'logo' ); ?></a>
		<ul id="menu-main-menu" class="nav navbar-nav">
			<?php 
				wp_nav_menu(array('theme_location' => 'main-menu', 'items_wrap' => '%3$s', 'container' => false));
				if ( is_user_logged_in() ) {
					if ( has_nav_menu('main-menu-privileged') ) wp_nav_menu(array('theme_location' => 'main-menu-privileged', 'items_wrap' => '%3$s', 'container' => false));
				}
			?>
		</ul>
		<form class="navbar-form navbar-right" role="search" method="get" id="searchform" action="<?php bloginfo('home'); ?>">
		<div class="form-group">
		  <input type="text" class="form-control" placeholder="Search" name="s" id="s">
		</div>
	  </form>
	  <ul class="nav navbar-nav navbar-right">
		<li class="dropdown archive" id="archive">
		  <a href="javascript:void(0);" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">Archive <span class="caret"></span></a>
		  <?php //get_template_part( 'archive', get_post_format()); ?>
		  <?php get_template_part( 'loading' ); ?>
		</li>
	  </ul>
	</div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>