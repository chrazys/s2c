<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Template for common archive pages, author and search results
 * @package themify
 * @since 1.0.0
 */
get_header();
if(!is_front_page() && !is_home()){
    get_template_part('includes/featured-area');
}
?>
<!-- layout -->
<div id="layout" class="pagewidth tf_clearfix">

    <?php themify_content_before(); //hook ?>
    
    <!-- content -->
    <main id="content" class="list-post">
	<?php themify_page_output(array('hide_title' => true, 'hide_desc' => true)); ?>
    </main>
    <?php 
    themify_content_after(); //hook 
    themify_get_sidebar(); ?>
</div>
<?php
get_footer();
