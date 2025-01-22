<?php
/**
 * Template for generic post display.
 * @package themify
 * @since 1.0.0
 */
$classes = array('post');
$cat =get_post_type()==='portfolio'? wp_get_object_terms( get_the_id(), 'portfolio-category' ):get_the_category(); 
if ( ! empty( $cat ) &&! is_wp_error( $cat ) ) {
    foreach ( $cat as $c ) {
	if ( is_object( $c ) && ($c->taxonomy === 'category' || $c->taxonomy === 'portfolio-category')) {
	    $classes[] = 'cat-'.$c->term_id;
	}
    }
    if ( !empty( $classes )){
	    $classes = implode(' ', $classes );
    }
}
?>
<?php themify_post_before(); // hook    ?>
<?php $media_position = strpos($themify->post_layout,'auto_tiles')!==false; ?>
<article id="post-<?php the_id(); ?>" <?php post_class($classes); ?>>

    <?php themify_post_start(); // hook   ?>

    <?php if ('below' !== $themify->media_position || $media_position) themify_post_media(); ?>

    <div class="post-content">
        <?php if($media_position &&  $themify->unlink_image !== 'yes'):?>
			<?php echo themify_open_link( array( 'class' => 'tiled_overlay_link' ) ); ?></a>
        <?php endif;?>
        <?php if ('below' === $themify->media_position && !$media_position) themify_post_media(); ?>

        <?php get_template_part('includes/post-meta', 'loop') ?>

    </div>
    <!-- /.post-content -->
    <?php themify_post_end(); // hook    ?>

</article>
<!-- /.post -->

<?php
themify_post_after(); // hook ?>
