<?php 
global $themify;
if (have_posts()){
    the_post(); ?>
<?php if(!is_singular() || is_page()):?>
	<?php  get_template_part('includes/category-description'); ?>
<?php else:?>
	<div class="featured-area fullcover">
			<?php if ($themify->post_layout_type && $themify->post_layout_type !== 'split' && $themify->post_layout_type !== 'fullwidth'): ?>
			   <?php if($themify->hide_image !== 'yes'):?>
					<?php get_template_part('includes/single-' . $themify->post_layout_type, 'single'); ?>
				<?php endif;?> 
			<?php else: ?>
				<?php themify_post_media(); ?>
			<?php endif; ?> 
			<div class="post-content">
				<?php get_template_part('includes/post-meta', 'single'); ?>
			</div>
		</div>
	<?php endif;?>
<?php }
rewind_posts();
