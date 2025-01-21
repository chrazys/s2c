<?php

include THEME_DIR.'/admin/panel/settings.php';

/**
 * Appearance Tab for Themify Custom Panel
 * @since 1.0.0
 * @return array
 */
function themify_theme_appearance_meta_box() {
	return array(
		
		// Header Wrap
		array(
			'name'          => 'header_wrap',
			'title'         => __( 'Header Background', 'themify' ),
			'description'   => '',
			'type'          => 'radio',
			'show_title'    => true,
			'meta'          => array(
				array(
					'value'    => 'solid',
					'name'     => __( 'Solid Background', 'themify' ),
					'selected' => true
				),
				array(
					'value' => 'transparent',
					'name'  => __( 'Transparent Background', 'themify' )
				)
			),
			'enable_toggle' => true,
			'class'     => 'hide-if none',
			'default' => 'solid'
		),
		// Background Color
		array(
			'name'        => 'background_color',
			'title'       => '',
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
			'toggle'      => 'solid-toggle',
			'class'     => 'hide-if none',
		),
		// Background image
		array(
			'name'        => 'background_image',
			'title'       => '',
			'type'        => 'image',
			'description' => '',
			'meta'        => array(),
			'before'      => '',
			'after'       => '',
			'toggle'      => 'solid-toggle',
			'class'     => 'hide-if none',
		),
		// Background repeat
		array(
			'name'        => 'background_repeat',
			'title'       => '',
			'description' => __( 'Background Repeat', 'themify' ),
			'type'        => 'dropdown',
			'meta'        => array(
				array(
					'value' => '',
					'name' => '',
				),
				array(
					'value' => 'fullcover',
					'name'  => __( 'Fullcover', 'themify' ),
					'selected' => true
				),
				array(
					'value' => 'repeat',
					'name'  => __( 'Repeat', 'themify' )
				),
				array(
					'value' => 'repeat-x',
					'name'  => __( 'Repeat horizontally', 'themify' )
				),
				array(
					'value' => 'repeat-y',
					'name'  => __( 'Repeat vertically', 'themify' )
				)
			),
			'toggle'      => 'solid-toggle',
			'class'     => 'hide-if none',
		),
		// Header wrap text color
		array(
			'name'        => 'headerwrap_text_color',
			'title'       => __( 'Header Text Color', 'themify' ),
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
			'class'     => 'hide-if none',
		),
		// Header wrap link color
		array(
			'name'        => 'headerwrap_link_color',
			'title'       => __( 'Header Link Color', 'themify' ),
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
			'class'     => 'hide-if none',
		),
            
                // Separator
		array(
			'name' => 'page_title_background',
			'type' => 'separator'
		),
                // Background Color
		array(
			'name'        => 'page_title_background_color',
			'title' => __( 'Page Title Background', 'themify' ).'&nbsp;',
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null )
		),
		// Background image
		array(
			'name'        => 'page_title_background_image',
			'title'       => '',
			'type'        => 'image',
			'description' => '',
			'meta'        => array(),
			'before'      => '',
			'after'       => ''
		)
	);
}
function themify_theme_setup_metaboxes($meta_boxes=array(), $post_type='all') {
    $supportedTypes=array('post', 'page','portfolio');
    $dir=THEME_DIR . '/admin/pages/';
    if($post_type==='all'){
	add_action('themify_settings_panel_end', 'themify_admin_script_style');
	foreach($supportedTypes as $s){
	    require_once( $dir . "$s.php" );
	}
	return $meta_boxes;
    }
    if (!in_array($post_type, $supportedTypes, true)) {
	return $meta_boxes;
    }
    themify_admin_script_style();
    require_once( $dir . "$post_type.php" );
    $theme_metaboxes = call_user_func_array( "themify_theme_get_{$post_type}_metaboxes", array( array(), &$meta_boxes ) );

    return array_merge($theme_metaboxes, $meta_boxes);
}


function themify_admin_script_style() {
    wp_enqueue_style('themify-admin-style', themify_enque(THEME_URI . '/admin/css/admin-style.css'), null, Themify_Enqueue_Assets::$themeVersion);
    wp_enqueue_script('themify-admin-script', themify_enque(THEME_URI . '/admin/js/admin-script.js'), null, Themify_Enqueue_Assets::$themeVersion, true);
}

/**
 * Register plugins required for the theme
 *
 * @since 1.0.0
 */
function themify_theme_register_required_plugins( $plugins ) {
	$plugins[] = array(
		'name'               => __( ' Themify Portfolio Posts', 'themify' ),
		'slug'               => 'themify-portfolio-post',
		'source'             => 'https://themify.me/files/themify-portfolio-post/themify-portfolio-post.zip',
		'required'           => true,
		'version'            => '1.0.0',
		'force_activation'   => false,
		'force_deactivation' => false,
	);
	return $plugins;
}

function themify_category_custom_fields($tag) {
        wp_enqueue_media();
        themify_enqueue_scripts('post.php');
        $category_meta = get_option('themify_category_bg');
        $post = get_posts(array('posts_per_page' => 1));
        $post = current($post);
        $img = isset($category_meta[$tag->term_id]['image']) && $category_meta[$tag->term_id]['image'];
        ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="category-bg"><?php _e("Background", 'themify'); ?></label></th>
        <td>
            <div id="themify_builder_alert" class="alert"></div>
            <div class="themify_field_row tf_clearfix  hide-if none">
                <div class="themify_field themify_field-color" style="width:auto;">
                    <span class="colorSelect"></span>
                    <input style="height: 33px;" type="text"  id="category-bg" name="category_meta[<?php echo $tag->term_id ?>][color]" value="<?php if (isset($category_meta[$tag->term_id])) esc_attr_e($category_meta[$tag->term_id]['color']); ?>"  class="themify_input_field colorSelectInput"/>
                    <input type="button" class="button clearColor" value="×"/>
                    &nbsp;&nbsp;<?php _e("image", 'themify'); ?>&nbsp;&nbsp;
                </div>
                <div class="themify_field" style="width:25%;margin-top: 7px;">
                    <div id="remove-themify_category_image" class="themify_featimg_remove <?php if (!$img): ?>hide<?php endif; ?>">
                        <a href="#"><?php _e("Remove image", 'themify'); ?></a>
                    </div>
                    <div class="themify_upload_preview"<?php if ($img): ?> style="display:block;"<?php endif; ?>>
                        <?php if ($img): ?>
                            <a href="<?php echo esc_url_raw($category_meta[$tag->term_id]['image']) ?>" target="_blank"> 
                                <img src="<?php echo esc_url_raw($category_meta[$tag->term_id]['image']) ?>" width="40" />
                            </a>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" id="themify_category_image" name="category_meta[<?php echo $tag->term_id ?>][image]" value="<?php echo $img ? esc_url_raw($category_meta[$tag->term_id]['image']) : '' ?>" class="themify_input_field themify_upload_field" />
                    <div class="themify_upload_buttons">
                        <?php themify_uploader('themify_category_image', array('preview' => true, 'tomedia' => true, 'medialib' => true, 'type' => 'image', 'fields' => 'themify_category_image', 'topost' => $post->ID)); ?>
                    </div>
                </div>
            </div>
            <script>
                jQuery(function ($) {
                    var $remove = $('#remove-themify_category_image');
                    $remove.find('a').on('click', function (e) {
                        e.preventDefault();
                        var $parent = $(this).parent().parent();
                        $parent.find('.themify_upload_field').val('');
                        $parent.find('.themify_upload_preview').fadeOut();
                        $remove.addClass('hide');
                    });
                });
            </script>
        </td>
    </tr>
    <?php
}

function themify_save_custom_fields() {

    if (isset($_POST['category_meta'])) {
        $id = key($_POST['category_meta']);
        $category_meta = get_option('themify_category_bg');
        $category_meta[$id] = $_POST['category_meta'][$id];
        if (!update_option('themify_category_bg', $category_meta)) {
            add_option('themify_category_bg', $_POST['category_meta']);
        }
    }
}

add_filter( 'themify_theme_required_plugins', 'themify_theme_register_required_plugins' );
if(isset( $_GET['page'] ) && $_GET['page']==='themify'){
    themify_theme_setup_metaboxes();
}
else{
    add_filter('themify_metabox/fields/themify-meta-boxes', 'themify_theme_setup_metaboxes', 10, 2);
}
add_action('edit_category_form_fields', 'themify_category_custom_fields');
add_action('edit_category', 'themify_save_custom_fields');
