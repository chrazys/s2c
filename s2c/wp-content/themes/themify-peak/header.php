<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php themify_body_start(); // hook ?>

	<div id="pagewrap" class="hfeed site">

		<div id="headerwrap">

			<?php themify_header_before(); // hook ?>

			<header id="header" class="pagewidth tf_clearfix">

				<?php themify_header_start(); // hook ?>

				<?php echo themify_logo_image(),themify_site_description(); ?>

				<div id="menu-wrapper">

					<?php if ( ! themify_check( 'setting-exclude_search_form' ) ) : ?>
						<div id="searchform-wrap">
							<?php get_search_form(); ?>
						</div>
						<!-- /#searchform-wrap -->
					<?php endif; ?>

					<div class="social-widget">
						<?php 
						dynamic_sidebar('social-widget');
						themify_theme_feed(array('text'=>''));
						?>
					</div>
					<!-- /.social-widget -->

					<nav id="main-nav-wrap" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
						<?php themify_menu_nav( array( 'walker' => new Themify_Mega_Menu_Walker ) ); ?>
					</nav>
					<!-- /#main-nav -->

				</div>
				<!-- /#menu-wrapper -->
				<a id="menu-icon" href="#mobile-menu"><span class="menu-icon-inner"></span></a>

				<div id="mobile-menu" class="sidemenu sidemenu-off tf_scrollbar">

					<?php themify_mobile_menu_start(); // hook ?>

					<div class="slideout-widgets">
						<?php dynamic_sidebar( 'slideout-widgets' ); ?>
						<a id="menu-icon-close" href="#mobile-menu"></a>
					</div>
					<!-- /.slideout-widgets -->

					<?php themify_mobile_menu_end(); // hook ?>

				</div>
				<!-- /#mobile-menu -->
				<?php themify_header_end(); // hook ?>

			</header>
			<!-- /#header -->

			<?php themify_header_after(); // hook ?>

		</div>
		<!-- /#headerwrap -->

		<div id="body" class="tf_clearfix">

			<?php themify_layout_before(); //hook 
