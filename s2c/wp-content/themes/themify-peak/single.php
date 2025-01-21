<?php
/**
 * Template for single post view
 * @package themify
 * @since 1.0.0
 */
get_header();

global $themify;
get_template_part('includes/featured-area');
?>
<!-- layout -->
<div id="layout" class="pagewidth tf_clearfix">
    <?php
    if (have_posts()) {
	the_post();
	?>
	<?php themify_content_before(); //hook ?>

        <!-- content -->
        <main id="content" class="list-post">
	    <?php themify_content_start(); // hook ?>
	    <?php themify_post_before(); // hook ?>
	    <article id="post-<?php the_Id(); ?>" <?php post_class('post tf_clearfix'); ?>>
		    <?php themify_post_start(); // hook  ?>
		<div class="post-content">
		    <div class="entry-content" itemprop="articleBody">
			<?php if ($themify->hide_meta !== 'yes' && get_post_type() === 'portfolio'): ?>
			    <?php get_template_part('includes/portfolio-meta', 'single'); ?>
			<?php endif; ?>
			<?php the_content(); ?>
		    </div>
		</div>
		    <?php themify_post_end(); // hook  ?>
	    </article>

	    <?php
	    
	    themify_post_after(); // hook 

	    wp_link_pages(array('before' => '<p class="post-pagination"><strong>' . __('Pages:', 'themify') . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number'));

	    get_template_part('includes/author-box', 'single');

	    get_template_part('includes/post-nav', 'single');

	    if ('none' !== themify_get('setting-relationship_taxonomy', false, true)){
		get_template_part('includes/related-posts', 'single'); 
	    }

	    themify_comments_template();

	    themify_content_end(); // hook  ?>
        </main>
	<?php themify_content_after(); //hook ?>
    <?php
    }
    themify_get_sidebar();
    ?>
</div>
<?php
get_footer();
