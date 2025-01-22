<?php
$shortcode = themify_get('post_layout_slider');
$slider=$shortcode?themify_get_gallery_shortcode($shortcode):false;
if (!$slider) {
	return;
}
$img_width = themify_get('image_width');
$img_height = themify_get('image_height');
$image_size = !$img_width?themify_get_gallery_shortcode_params($shortcode, 'size'):'full';
?>
<div class="single-slider">
	<div data-lazy="1" data-auto="5000" class="tf_carousel tf_swiper-container slides tf_overflow">
        <div class="tf_swiper-wrapper tf_lazy tf_rel tf_w tf_h">
		<?php foreach ($slider as $image): ?>
			<?php
			$alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
			$caption = $image->post_excerpt ? $image->post_excerpt : $image->post_content;
			if (!$alt) {
				$alt = get_post_meta($image->ID, '_wp_attachment_image_title', true);
			}
			if (!$caption) {
				$caption = $alt;
			}
			
			$img = wp_get_attachment_image_src($image->ID, $image_size);
			$img = themify_get_image(array('w'=>$img_width,'h'=>$img_height,'src'=>$img[0],'alt'=>$alt,'crop'=>true,'is_slider'=>true));
			?>
			<div class="tf_lazy tf_swiper-slide">
				<?php echo $img ?>
				<?php if ($caption): ?>
					<p class="flex-caption"><?php echo esc_html( $caption ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
        </div>
    </div>
</div>
