<?php
/**
 * Template for site footer
 * @package themify
 * @since 1.0.0
 */
?>

				<?php themify_layout_after(); // hook ?>
			</div>
			<!-- /body -->
                        <div class="footer-widgets-wrap">
                            <?php get_template_part( 'includes/footer-widgets' ); ?>
                            <div class="back-top tf_clearfix">
                                    <div class="arrow-up">
                                            <a href="#header"><span class="screen-reader-text"><?php _e('Back to top', 'themify'); ?></span></a>
                                    </div>
                            </div>
                        </div>
			<div id="footerwrap">

				<?php themify_footer_before(); // hook ?>

				<footer id="footer" class="pagewidth">

					<?php themify_footer_start(); // hook ?>
					
					<?php if (!themify_get('setting-exclude_footer_menu')) : ?>
						<div class="footer-nav-wrap">
							<?php themify_menu_nav( array(
								'theme_location' => 'footer-nav',
								'fallback_cb' => '',
								'container'  => '',
								'menu_id' => 'footer-nav',
								'menu_class' => 'footer-nav',
							)); ?>
						</div>
						<!-- /.footer-nav-wrap -->
					<?php endif; // exclude menu navigation ?>
					<div class="footer-text tf_clearfix">
						<?php themify_the_footer_text(); ?>
						<?php themify_the_footer_text('right'); ?>
					</div>
					<!-- /footer-text -->
					<?php themify_footer_end(); // hook ?>

				</footer>
				<!-- /#footer -->

				<?php themify_footer_after(); // hook ?>

			</div>
			<!-- /#footerwrap -->

		</div>
		<!-- /#pagewrap -->
        <?php themify_body_end(); // hook ?>		
        <!-- wp_footer -->
		<?php wp_footer(); ?>
		
	</body>
</html>
