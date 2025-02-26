<?php
/* * *************************************************************************
 *  					Theme Functions
 * 	----------------------------------------------------------------------
 * 						DO NOT EDIT THIS FILE
 * 	----------------------------------------------------------------------
 * 
 *  					Copyright (C) Themify
 * 						https://themify.me
 *
 *  To add custom PHP functions to the theme, create a child theme (https://themify.me/docs/child-theme) and add it to the child theme functions.php file.
 *  They will be added to the theme automatically.
 * 
 * ************************************************************************* */


/* * ********************
 * Plugin Integration *
 * ******************** */
/** Compatibility with portfolio posts plugin */
const THEMIFY_PORTFOLIO_POSTS_COMPAT_MODE=true;
/////// Actions ////////
// Init post, page and additional post types if they exist
add_action('after_setup_theme', 'themify_theme_after_setup_theme');


// Register sidebars
add_filter('themify_register_sidebars','themify_theme_register_sidebars');

// Exclude CPT for sidebar
add_filter( 'themify_exclude_CPT_for_sidebar', 'themify_CPT_exclude_sidebar' );

/////// Filters ////////

/**
 * Enqueue Stylesheets and Scripts
 * @since 1.0.0
 */
function themify_theme_enqueue_footer() {
	
	// Prepare JS variables
	$themify_script_vars = array(
		'sticky_header'=>themify_theme_sticky_logo(),
		// Like it
		'ajax_nonce' => wp_create_nonce('ajax_nonce'),
		'autoInfinite' => themify_check('setting-autoinfinite',true) ? 'no' : 'auto',
		'infiniteURL' => !themify_check( 'setting-infinite-url',true ) ? 1 :''
	);
	// Pass variable values to JavaScript
	wp_localize_script('themify-main-script', 'themifyScript', apply_filters('themify_script_vars', $themify_script_vars));
}

/**
 * Load Google fonts used by the theme
 *
 * @return array
 */
function themify_theme_google_fonts( $fonts ) {
	/* translators: If there are characters in your language that are not supported by Yantramanav, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Yantramanav font: on or off', 'themify' ) ) {
		$fonts['yantramanav'] = 'Yantramanav:400,300,500,700';
	}
	/* translators: If there are characters in your language that are not supported by Suranna, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Suranna font: on or off', 'themify' ) ) {
		$fonts['suranna'] = 'Suranna';
	}

	return $fonts;
}
add_filter( 'themify_google_fonts', 'themify_theme_google_fonts' );


if (!function_exists('themify_theme_after_setup_theme')) {

    /**
     * Register theme support.
     *
     * Initialize custom panel with its definitions.
     * Custom panel definitions are located in admin/post-type-TYPE.php
     *
     * @since 1.0.7
     */
    function themify_theme_after_setup_theme() {
        // Enable WordPress feature image
        add_theme_support('post-thumbnails');

        // Load Themify Mega Menu
	add_theme_support( 'themify-mega-menu' );
	if(themify_is_woocommerce_active()){
	    //add wc css 
	    Themify_Enqueue_Assets::add_theme_support_css( 'wc' );
	}
	Themify_Enqueue_Assets::remove_theme_support_css('rtl');
        require_once( THEME_DIR.'/admin/post-type-portfolio.php' );
	
        register_nav_menus(array(
            'main-nav' => __('Main Navigation', 'themify'),
            'footer-nav' => __('Footer Navigation', 'themify'),
        ));
    }

}
if (!function_exists('themify_theme_body_class')) {

    /**
     * Adds body classes for special theme features.
     *
     * @param $classes
     *
     * @return array
     */
    function themify_theme_body_class($classes) {
        global $themify;
        //Single post Layout 
        if (is_single()) {
            $layout = $themify->post_layout_type;
            if (!$layout) {
                $layout = 'default';
            } elseif ($layout === 'split') {
                foreach ($classes as $key => $class) {
                    if (false !== stripos($class, 'sidebar')) {
                        unset($classes[$key]);
                    }
                }
                $classes[] = 'sidebar-none';
            }
            $classes[] = 'single-' . $layout . '-layout';
        } 
        // Add transparent-header class to body if user selected it in custom panel
        if (( is_single() || is_page() || themify_is_shop() ) && 'transparent' == themify_get('header_wrap')) {
            $classes[] = 'transparent-header';
        }
        // Header Design
        $header = themify_area_design('header');
        $classes[] = 'none' === $header ? 'header-none' : $header;

	if( 'none' !== $header && themify_theme_fixed_header() ) {
		$classes[] = 'fixed-header-enabled';
	}

        // Check if user disabled drop caps
        $classes[] = themify_check('setting-disable_drop_cap',true) ? 'disable-drop-cap' : 'enable-drop-cap';

        if (!is_active_sidebar('slideout-widgets')) {
            $classes[] = 'slideout-widgets-empty';
        }
        return $classes;
    }

    add_filter('body_class', 'themify_theme_body_class', 99);
}
/**
 * Checks liker's IP and saves it to the post if it's not already in likers list.
 * @since 2.2.6
 */
function themify_likeit() {
    check_ajax_referer('ajax_nonce', 'nonce');

    $post_id = $_POST['post_id'];

    $ip = $_SERVER['REMOTE_ADDR'];

    $current_likers = trim(get_post_meta($post_id, 'likers', true));

    if (isset($current_likers) && '' != $current_likers) {
        $current_likers_count = count(explode(',', $current_likers));
    } else {
        $current_likers_count = 0;
    }

    if (false === stripos($current_likers, $ip)) {
        if (isset($current_likers) && '' != $current_likers)
            $save_likers = $current_likers . ',' . $ip;
        else
            $save_likers = $ip;

        $update_result = update_post_meta($post_id, 'likers', $save_likers);
        update_post_meta($post_id, '_themify_likes_count', $current_likers_count + 1);

        if (is_multisite()) {
            $msblogid = get_post_meta($post_id, 'blogid', true);
            $mspostid = get_post_meta($post_id, 'postid', true);
            switch_to_blog($msblogid);
            update_post_meta($mspostid, 'likers', $save_likers);
            update_post_meta($mspostid, '_themify_likes_count', $current_likers_count + 1);
            restore_current_blog();
        }

        if ($update_result) {
            echo json_encode(array(
                'status' => 'new',
                'likers' => $current_likers_count + 1,
                'ip' => $ip
            ));
        } else {
            echo json_encode(array(
                'status' => 'failed',
                'ip' => $ip
            ));
        }
    } else {
        echo json_encode(array(
            'status' => 'isliker',
            'ip' => $ip
        ));
    }

    die();
}

add_action('wp_ajax_themify_likeit', 'themify_likeit');
add_action('wp_ajax_nopriv_themify_likeit', 'themify_likeit');

/**
 * Return number of likers or 0
 * @param bool $echo Whether to echo or just return
 * @return string
 * @since 2.2.6
 */
function themify_get_like($echo = true) {
    if ($current_likers = themify_get('_themify_likes_count')) {
        $count = $current_likers;
    } else {
        $count = '0';
    }
    if ($echo)
        echo $count;
    return $count;
}


/**
 * Register sidebars
 * @since 1.0.0
 */
function themify_theme_register_sidebars($sidebars) {
     $sidebars[]=array(
	'name' => __('Slideout Widgets', 'themify'),
	'id' => 'slideout-widgets',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h4 class="widgettitle">',
	'after_title' => '</h4>'
    );
    return $sidebars;
}


/**
 * Set portfolio post type slug
 *
 * @since 1.0.0
 *
 * @return string
 */
function themify_theme_portfolio_post_rewrite($slugs) {
	$slug = themify_get( 'themify_portfolio_slug' );
    if(is_array($slugs)){
        $slugs['post'] = empty( $slug ) ? apply_filters( 'themify_portfolio_rewrite', $slugs['post'] ) : $slug;
		$category_slug = themify_get( 'themify_portfolio_category_slug' );
		$slugs['tax'] = empty( $category_slug ) ? apply_filters( 'themify_portfolio_category_rewrite', $slugs['tax'] ) : $category_slug;    
    }else{
		$slugs = empty( $slug ) ? apply_filters( 'themify_portfolio_rewrite', 'project' ) : $slug;
    }
	return $slugs;
}

add_filter('themify_portfolio_post_rewrite', 'themify_theme_portfolio_post_rewrite');


if( ! function_exists('themify_CPT_exclude_sidebar') ) {
	/**
	 * Exclude Custom Post Types
	 */
	function themify_CPT_exclude_sidebar($CPT = array()) {
		$CPT[] = 'portfolio';
		if(themify_is_woocommerce_active()){
			$CPT[] = 'product';
		}
		return $CPT;
	}
}

if (!function_exists('themify_theme_custom_post_css')) {

    /**
     * Outputs custom post CSS at the end of a post
     * @since 1.0.0
     */
    function themify_theme_custom_post_css() {
        global $themify;
        if ( ( !is_category() && in_array(get_post_type(), array('post', 'page')) ) || themify_is_shop() ) {
            if( themify_is_shop() ) {
				$entry_id = '.post-type-archive-product';
			} else {
				$post_id = get_the_ID();
				$entry_id = is_page() ? '.page-id-' : '.postid-';
				$entry_id.= $post_id;
			}
            $headerwrap = $entry_id . ' #headerwrap';
            $site_logo = $entry_id . ' #site-logo';
            $site_description = $entry_id . ' #site-description';
            $main_nav = $entry_id . ' #main-nav';
            $social_widget = $entry_id . ' .social-widget';
            $css = array();
            $style = '';
            $rules = array();

            if ('transparent' != themify_get('header_wrap')) {
                $rules = array(
                    $headerwrap => array(
                        array(
                            'prop' => 'background-color',
                            'key' => 'background_color'
                        ),
                        array(
                            'prop' => 'background-image',
                            'key' => 'background_image'
                        ),
                        array(
                            'prop' => 'background-repeat',
                            'key' => 'background_repeat',
                            'dependson' => array(
                                'prop' => 'background-image',
                                'key' => 'background_image'
                            ),
                        ),
                    ),
                    "$entry_id #site-logo span:after, $entry_id #headerwrap #searchform, $entry_id #main-nav .current_page_item a, $entry_id #main-nav .current-menu-item a" => array(
                        array(
                            'prop' => 'border-color',
                            'key' => 'headerwrap_text_color'
                        ),
                    ),
                );
            }

            $rules["$headerwrap, $site_logo, $site_description"] = array(
                array(
                    'prop' => 'color',
                    'key' => 'headerwrap_text_color'
                ),
            );

            $rules["$site_logo a, $site_description a, $social_widget a, $main_nav > li > a"] = array(
                array(
                    'prop' => 'color',
                    'key' => 'headerwrap_link_color'
                ),
            );

            if (is_singular(array('portfolio', 'event'))) {
                $rules['.postid-' . $post_id . ' .featured-area'] = array(
                    array('prop' => 'background-color',
                        'key' => 'featured_area_background_color'
                    ),
                    array('prop' => 'background-image',
                        'key' => 'featured_area_background_image'
                    ),
                    array('prop' => 'background-repeat',
                        'key' => 'featured_area_background_repeat',
                        'dependson' => array(
                            'prop' => 'background-image',
                            'key' => 'featured_area_background_image'
                        ),
                    ),
                );
                $rules['.postid-' . $post_id . ' .portfolio-post-wrap, .postid-' . $post_id . ' .portfolio-post-wrap .post-date'] = array(
                    array('prop' => 'color',
                        'key' => 'featured_area_text_color'
                    ),
                );
                $rules['.postid-' . $post_id . ' .portfolio-post-wrap a'] = array(
                    array('prop' => 'color',
                        'key' => 'featured_area_link_color'
                    ),
                );
            }

            foreach ($rules as $selector => $property) {
                foreach ($property as $val) {
                    $prop = $val['prop'];
                    $key = $val['key'];
                    if (is_array($key)) {
                        if ($prop == 'font-size' && themify_check($key[0])) {
                            $css[$selector][$prop] = $prop . ': ' . themify_get($key[0]) . themify_get($key[1]);
                        }
                    } elseif (themify_check($key) && 'default' != themify_get($key)) {
                        if ($prop == 'color' || stripos($prop, 'color')) {
                            $css[$selector][$prop] = $prop . ': ' . themify_get_color($key);
                        } elseif ($prop == 'background-image' && 'default' != themify_get($key)) {
                            $css[$selector][$prop] = $prop . ': url(' . themify_get($key) . ')';
                        } elseif ($prop == 'background-repeat' && 'fullcover' == themify_get($key)) {
                            if (isset($val['dependson'])) {
                                if ($val['dependson']['prop'] == 'background-image' && ( themify_check($val['dependson']['key']) && 'default' != themify_get($val['dependson']['key']) )) {
                                    $css[$selector]['background-size'] = 'background-size: cover';
                                }
                            } else {
                                $css[$selector]['background-size'] = 'background-size: cover';
                            }
                        } elseif ($prop == 'font-family') {
                            $font = themify_get($key);
                            $css[$selector][$prop] = $prop . ': ' . $font;
                            if (!in_array($font, themify_get_web_safe_font_list(true))) {
                                $themify->google_fonts .= str_replace(' ', '+', $font . '|');
                            }
                        } else {
                            $css[$selector][$prop] = $prop . ': ' . themify_get($key);
                        }
                    }
                }
                if (!empty($css[$selector])) {
                    $style .= "$selector {\n\t" . implode(";\n\t", $css[$selector]) . "\n}\n";
                }
            }
            if (is_page()) {

                $img = themify_get('page_title_background_image');
                if ($img) {
                    $style.= '.page-category-title-wrap{background: url("' . esc_url( themify_https_esc( $img ) ) . '") no-repeat; background-size: cover;}';
                }
                $color = themify_get_color('page_title_background_color');
                if ($color) {
                    if($img){
                        $style.= '.category-title-overlay{background-color: ' . $color . ';}';
                    }
                    else{
                        $style.= '.category-title-overlay{background: none;}.page-category-title-wrap{background-color: ' . $color . ';}';
                    }
                }
            }
            if ('' != $style) {
                echo "\n<!-- Entry Style -->\n<style>\n$style</style>\n<!-- End Entry Style -->\n";
            }
        }
        elseif(is_category()){
                $categories = get_category(get_query_var('cat'));
                $category_id = $categories->cat_ID;
                $category_meta = get_option( 'themify_category_bg' );
                if(isset($category_meta[$category_id])){
                        $style = '';
                        if(isset($category_meta[$category_id]['image']) && $category_meta[$category_id]['image']){
                                $style = '.page-category-title-wrap{background:url("'.esc_url( themify_https_esc( $category_meta[$category_id]['image'] ) ).'") no-repeat;background-size: cover;}';
                        }
                        if($category_meta[$category_id]['color'] && $category_meta[$category_id]['color']){
                                if($style){
                                        $style.= '.category-title-overlay{background-color:#'.$category_meta[$category_id]['color'].';}';
                                }
                                else{
                                        $style= '.category-title-overlay{background:0;}.page-category-title-wrap{background-color:#'.$category_meta[$category_id]['color'].';}';
                                }
                        }
                        if($style){
                                echo "\n<!-- Entry Style -->\n<style>\n$style</style>\n<!-- End Entry Style -->\n";
                        }
                }
        }
    }

    add_action('wp_head', 'themify_theme_custom_post_css', 77);
}



/**
 * Miltiply infinity scroll for shortcodes themify_list_posts,themify_portfolio_posts
 */
// add the filter
add_filter( "shortcode_atts_themify_list_posts", 'themify_list_posts_attrs', 10, 4 );
add_filter('themify_list_posts_shortcode_query_args', 'themify_infinity_shortcode', 10, 2);
add_filter('themify_portfolio_shortcode_args', 'themify_infinity_shortcode', 10, 2);
add_action('wp_ajax_themify_shortcode_infinity', 'themify_shortcode_infinity');
add_action('wp_ajax_nopriv_themify_shortcode_infinity', 'themify_shortcode_infinity');
if (!function_exists('themify_infinity_shortcode')) {

    /**
     * Add infinite_scroll value in themify_list_posts shortcode
     * @return void
     */
    function themify_infinity_shortcode($query_args, $atts) {

        global $themify;
        $themify->infinity_query = $themify->infinity_count = false;
        if (isset($atts['load_more']) && $atts['load_more'] !== 'no') {
            $args = $query_args;
            $args['fields'] = 'ids';
            $query = new WP_Query($args);
            $themify->infinity_count = $query->max_num_pages;
            foreach ($atts as $k => $v) {
                if ($v !== '') {
                    $themify->infinity_query[$k] = $v;
                }
            }
            wp_reset_postdata();
        }
		add_filter('themify_get_shortcode_template', 'themify_add_shortcode_pagination', 10, 2);
		$themify->post_filter = ! empty( $atts['post_filter'] )?'yes':'no';

        return $query_args;
    }

    function themify_add_shortcode_pagination($html) {
		global $themify;
        if ($themify->post_filter==='yes') {
			$themify->shortcode_query_taxonomy = 'category';
            ob_start();
			locate_template('includes/filter.php', true, false);
            $html = ob_get_contents() . $html;
            ob_end_clean();
        } else {
            $themify->shortcode_query_taxonomy = null;
        }
        if ($themify->infinity_count > 1) {
            ob_start();
            locate_template('includes/pagination.php', true, false);
            $html.= ob_get_contents();
            ob_end_clean();
        }
        return $html;
    }

    function themify_shortcode_infinity() {
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            if (isset($query['load_more']) && $query['load_more'] !== 'no') {
                $is_portfolio = isset($query['is_portfolio']);
                $shortcode = $is_portfolio ? '[themify_portfolio_posts ' : '[themify_list_posts ';
                foreach ($query as $k => $v) {
                    if ($v || "$v"==='0') {
                        if (is_array($v)) {
                            $v = implode(',', $v);
                        }
                        $shortcode.=' ' . $k . '="' . esc_attr($v) . '"';
                    }
                }
                if (isset($_GET['paging']) && $_GET['paging'] > 1) {
                    $page = intval($_GET['paging']);
                    $limit = isset($query['limit']) && $query['limit'] > 0 ? intval($query['limit']) : get_option('posts_per_page');
                    $offset = $limit * ($page - 1);
                    $shortcode.=' offset="' . $offset . '"';
                }
                $shortcode.=' ]';
                ob_start();
                echo do_shortcode($shortcode);
                locate_template('includes/pagination.php', true, false);
                ob_end_flush();
            }
        }
        wp_die();
    }

}
// Add load_more, post_filter to themify_list_posts shortcode attrs
if(!function_exists('themify_list_posts_attrs')){
    function themify_list_posts_attrs( $out, $pairs, $attrs, $shortcode ) {
        if(!empty($attrs['load_more'])){
            $out['load_more']=$attrs['load_more'];
        }
        if(!empty($attrs['post_filter'])){
            $out['post_filter']=$attrs['post_filter'];
        }
        return $out;
    }
}
function themify_adjust_avatar() {
    return 125;
}
add_filter( 'themify_author_box_avatar_size', 'themify_adjust_avatar' );

/**
 * Settings module extension for post builder
 */
if( ! function_exists( 'themify_builder_module_settings_options' ) ) :
function themify_builder_module_settings_options( $options, $module ) {
	if( is_object( $module ) && ($module->slug==='post' || $module->slug==='portfolio') ) {
		$extra_options =  array();
		// Tiles Layout
		$bindig = array(
			'not_empty' => array(
				'show' => array('post_content_layout','disable_masonry ','post_gutter')
			),
			'list-post' => array(
				'hide' => array('disable_masonry ','post_gutter'),
				'show' => array('post_content_layout')
			),
			'auto_tiles' => array(
				'hide' => array('disable_masonry','post_gutter'),
				'show' => array('post_content_layout')
			),
			'list-thumb-image' => array(
				'hide' => array( 'post_content_layout', 'disable_masonry', 'post_gutter' )
			),
			'grid2-thumb' => array(
				'hide' => array( 'post_content_layout', 'disable_masonry', 'post_gutter' )
			)
		);
		foreach( $options as $k => $opt ) {
		
			if(isset($opt['id']) && $opt['id'] === 'layout_' . $module->slug){
				$options[$k]['options'][] = array('value' => 'auto_tiles', 'img' => THEME_URI . '/images/layout-icons/auto-tiles.png', 'label' => __( 'Tiles', 'themify' ));
				if(!isset($options[$k]['binding'])){
					$options[$k]['binding']=array();
				}
				$options[$k]['binding'] = array_merge($options[$k]['binding'] , $bindig);
				$index = $k;
				break;
			}
		}
		// Content Layout
		$extra_options[] = array(
			'id' => $module->slug . '_content_layout',
			'type' => 'select',
			'label' => __( 'Post Content Layout', 'themify' ),
			'options' => array(
				'default' => __( 'Default', 'themify' ),
				'overlay' => __( 'Overlay', 'themify' ),
				'polaroid' => __( 'Polaroid', 'themify' ),
			)
		);

		// Filter
		$extra_options[] = array(
			'id' => $module->slug . '_filter',
			'type' => 'toggle_switch',
			'default' => 'on',
			'label' => __( 'Post Filter', 'themify' ),
			'options' =>'simple',
			'wrap_class' => 'tb_group_element_category'
		);

		// Masonry
		$extra_options[] = array(
			'id' => 'disable_masonry',
			'type' => 'select',
			'label' => __( 'Post Masonry', 'themify' ),
			'options' => array(
				'default' => __( 'Default', 'themify' ),
				'yes' => __( 'Yes', 'themify' ),
				'no' => __( 'No', 'themify' )
			),
			'wrap_class' => 'tb_group_element_grid4 tb_group_element_grid3 tb_group_element_grid2 tb_group_element_list-large-image'
		);
		
		// Gutter
		$extra_options[] = array(
			'id' => $module->slug . '_gutter',
			'type' => 'select',
			'label' => __( 'Post Gutter', 'themify' ),
			'options' => array(
				'default' => __( 'Default', 'themify' ),
				'gutter' => __( 'Gutter', 'themify' ),
				'no-gutter' => __( 'No gutter', 'themify' )
			)
		);
		array_splice( $options, ++$index, 0, $extra_options );
	}
	
	return $options;
}
endif;
add_filter( 'themify_builder_module_settings_fields', 'themify_builder_module_settings_options', 10, 2 );



/**
 * Outputs classes based on certain user-specified parameters.
 *
 * @since 1.0.0
 */
function themify_theme_loops_wrapper_class($class,$post_type,$layout,$type,$moduleArgs=array(),$slug=false) {
	if($type!=='shortcode'){
		if('custom_tiles'===$layout){
			$layout='auto_tiles';
		}
		if($post_type===''){
			$post_type='post';
		}
		$isMasonry=$layout!=='auto_tiles' && $layout!=='slider' && $layout!=='list-thumb-image' && $layout!=='grid2-thumb';
		$gutter=$isMasonry===true && $layout!=='list-large-image';
		if($type==='builder'){
			if($slug === 'post' || $slug === 'portfolio' ){
				if($isMasonry===true){
					$isMasonry = ! empty( $moduleArgs['disable_masonry'] )? $moduleArgs['disable_masonry']:false;
					if($isMasonry==='default'){
						$isMasonry = $slug==='portfolio'?themify_get('setting-portfolio_disable_masonry','',true):themify_get('setting-disable_masonry','',true);
					}
				}
				if($gutter===true){
					$gutter=! empty( $moduleArgs[$slug . '_gutter'] )? $moduleArgs[$slug . '_gutter']:false;
					if($gutter==='default'){
						$gutter = themify_get( 'setting-' . $slug . '_gutter',false,true );
					}
				}
				if($layout!=='list-large-image' && $layout!=='list-thumb-image' && $layout!=='grid2-thumb'){
					$content_layout=!empty($moduleArgs[$slug . '_content_layout'])?$moduleArgs[$slug.'_content_layout']:false;
					if($content_layout==='default'){
						$content_layout= themify_get( 'setting-'.$slug.'_content_layout',false,true );
					}
					if($content_layout && $content_layout!=='default'){
						$class[]=$content_layout;
					}
				}
			}
		}
		elseif($post_type==='portfolio' || $post_type==='post'){
			if($isMasonry===true){
				$isMasonry = $post_type==='portfolio'?themify_get_both('portfolio_disable_masonry','setting-portfolio_disable_masonry'):themify_get_both('disable_masonry','setting-disable_masonry');
			}
			if($gutter===true){
				$gutter = themify_get_both($post_type . '_gutter','setting-' . $post_type . '_gutter',false);
			}
		}
		if($isMasonry===true || $isMasonry==='yes'){
			$class[]='masonry';
		}
		if($gutter===true || $gutter==='no-gutter'){
			$class[]='no-gutter';
		}
	}
    return $class;
}

add_filter('themify_loops_wrapper_class','themify_theme_loops_wrapper_class',10,6);

function themify_theme_fixed_header() {
        static $fixed = null;
        if($fixed===null){
            if ( is_singular( array( 'post', 'page', 'portfolio' ) ) ) {
                    $fixed_header_field = themify_get( 'fixed_header' );
                    if ( 'yes' === $fixed_header_field ) {
                        $fixed = 'fixed-header';
                        return $fixed;
                    } elseif ( 'no' === $fixed_header_field ) {
                            $fixed = '';
                            return $fixed;
                    }
            }
            $fixed = themify_check( 'setting-fixed_header_disabled',true ) ? '' : 'fixed-header';
        }
        return $fixed;
}

function themify_theme_sticky_logo(){
     if(themify_theme_fixed_header()){
        global $themify_customizer;
        $logo = json_decode($themify_customizer->get_cached_mod('sticky_header_imageselect'));
        return isset($logo->src) && '' != $logo->src?$logo:false;
     }
     else{
         return false;
     }
}
