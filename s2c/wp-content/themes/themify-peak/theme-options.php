<?php

/**
 * Main Themify class
 *
 * @package themify
 * @since 1.0.0
 */
class Themify {

    /** Default sidebar layout
     *
     * @var string
     */
    public $layout;
    public $post_layout;
    public $post_layout_type = '';
    public $hide_title;
    public $hide_meta;
    public $hide_meta_author;
    public $hide_meta_category;
    public $hide_meta_comment;
    public $hide_meta_tag;
    public $hide_date;
    public $inline_date;
    public $hide_image;
    public $media_position;
    public $unlink_title;
    public $unlink_image;
    public $display_content = '';
    public $auto_featured_image;
    public $post_image_width = '';
    public $post_image_height = '';
    public $width = '';
    public $height = '';
    public $image_size = '';
    public $avatar_size = 32;
    public $single_avatar_size = 60;
    public $page_navigation;
    public $posts_per_page;
    public $page_id = '';
    public $query_category = '';
    public $query_post_type = '';
    public $query_taxonomy = '';
    public $paged = '';
    public $google_fonts;
    public $post_filter = false;
    public $query_all_post_types;
    private static $page_image_width = 978;
    // Default Single Image Size
    private static $single_image_width = 1400;
    private static $single_image_height = 700;
    // List Post
    private static $list_post_width = 1160;
    private static $list_post_height = 665;
    // Grid4
    private static $grid4_width = 260;
    private static $grid4_height = 150;
    // Grid3
    private static $grid3_width = 360;
    private static $grid3_height = 205;
    // Grid2
    private static $grid2_width = 561;
    private static $grid2_height = 321;
    // List Large
    private static $list_large_image_width = 800;
    private static $list_large_image_height = 460;
    // List Thumb
    private static $list_thumb_image_width = 260;
    private static $list_thumb_image_height = 150;
    // List Grid2 Thumb
    private static $grid2_thumb_width = 160;
    private static $grid2_thumb_height = 95;
    // Use dimensions defined in custom post type panel
    public $use_original_dimensions = 'no';
    // Sorting Parameters
    public $order = 'DESC';
    public $orderby = 'date';
    public $order_meta_key = false;
    // Check whether object in shortcode loop
    public $is_shortcode = false;
    // Single Portfolio
    private static $single_portfolio_image_width = 1400;
    private static $single_portfolio_image_height = 700;
    public $infinity_count;
    
    public $page_title;
    public $image_page_single_width;
    public $image_page_single_height;
    public $hide_page_image;
    public $excerpt_length;
    public $isPage=false;

    function __construct() {
	add_action('template_redirect', array($this, 'template_redirect'), 5);
    }

    private function themify_set_global_options() {

	$this->layout = themify_get('setting-default_layout', 'sidebar1', true);
	$this->post_layout = themify_get('setting-default_post_layout', 'auto_tiles', true);
	$this->post_layout_type = themify_get('setting-post_content_layout', $this->post_layout_type, true);

	$this->hide_title = themify_get('setting-default_post_title', '', true);
	$this->unlink_title = themify_get('setting-default_unlink_post_title', '', true);
	$this->media_position = themify_get('setting-default_media_position', 'above', true);
	$this->hide_image = themify_get('setting-default_post_image', '', true);
	$this->unlink_image = themify_get('setting-default_unlink_post_image', '', true);
	$this->auto_featured_image = themify_check('setting-auto_featured_image', true);

	$this->hide_meta = themify_get('setting-default_post_meta', '', true);
	$this->hide_meta_author = themify_get('setting-default_post_meta_author', '', true);
	$this->hide_meta_category = themify_get('setting-default_post_meta_category', '', true);
	$this->hide_meta_comment = themify_get('setting-default_post_meta_comment', '', true);
	$this->hide_meta_tag = themify_get('setting-default_post_meta_tag', '', true);

	$this->hide_date = themify_get('setting-default_post_date', 'no', true);
	$this->inline_date = $this->hide_date === 'yes' ? false : themify_get('setting-default_display_date_inline', false, true);

	// Set Order & Order By parameters for post sorting
	$this->order = themify_get('setting-index_order', $this->order, true);
	$this->orderby = themify_get('setting-index_orderby', $this->orderby, true);

	if ($this->orderby === 'meta_value' || $this->orderby === 'meta_value_num') {
	    $this->order_meta_key = themify_get('setting-index_meta_key', '', true);
	}

	$this->display_content = themify_get('setting-default_layout_display', '', true);
	$this->excerpt_length = themify_get('setting-default_excerpt_length', '', true);
	$this->avatar_size = apply_filters('themify_author_box_avatar_size', $this->avatar_size);
	$this->single_avatar_size = apply_filters('themify_author_box_single_avatar_size', $this->single_avatar_size);
	$this->posts_per_page = get_option('posts_per_page');
	$this->width = themify_get('setting-image_post_width', '', true);
	$this->height = themify_get('setting-image_post_height', '', true);
    }

    function template_redirect() {
	$this->themify_set_global_options();
	if (is_singular()) {
	    $this->display_content = 'content';
	}

	if (is_page() || themify_is_shop()) {
	    if (post_password_required()) {
		return;
	    }
	    $this->page_id = get_the_ID();
	    // Set Page Number for Pagination
	    $this->paged = get_query_var('paged');
	    if (empty($this->paged)) {
		$this->paged = get_query_var('page', 1);
	    }
	    global $paged;
	    $paged = $this->paged;

	    $this->layout = themify_get_both('page_layout', 'setting-default_page_layout', 'sidebar1');
	    $this->page_title = themify_get_both('hide_page_title', 'setting-hide_page_title', 'no');
	    $this->hide_page_image = themify_get('setting-hide_page_image', false, true) === 'yes' ? 'yes' : 'no';
	    $this->image_page_single_width = themify_get('setting-page_featured_image_width', self::$page_image_width, true);
	    $this->image_page_single_height = themify_get('setting-page_featured_image_height', 0, true);

	    if(!themify_is_shop()){
		// Post query query ///////////////////
		$post_query_category = themify_get('query_category', '');
		$portfolio_query_category = themify_get('portfolio_query_category', '');

		if ('' !== $portfolio_query_category) {

		    // GENERAL QUERY POST TYPES
		    $this->query_category = $portfolio_query_category;
		    $this->query_post_type = 'portfolio';
		    $this->use_original_dimensions = 'no';
		    $this->query_taxonomy = $this->query_post_type . '-category';
		    $this->post_layout = themify_get('portfolio_layout','auto_tiles');
		    $this->post_layout_type = themify_get_both('portfolio_content_layout', 'setting-portfolio_content_layout');
		    $this->hide_meta = themify_get_both('portfolio_hide_meta_all', 'setting-default_portfolio_index_post_meta_category', 'no');
		    $this->hide_title = themify_get_both('portfolio_hide_title', 'setting-default_portfolio_index_title', 'no');
		    $this->unlink_title = themify_get_both('portfolio_unlink_title', 'setting-default_portfolio_index_unlink_post_title', 'no');
		    $this->unlink_image = themify_get_both('portfolio_unlink_image', 'setting-default_portfolio_index_unlink_post_image', 'no');
		    $this->hide_image = themify_get_both('portfolio_hide_image', 'setting-default_portfolio_index_post_image', 'no');
		    $this->width = themify_get_both('portfolio_image_width', 'setting-default_portfolio_index_image_post_width', '');
		    $this->height = themify_get_both('portfolio_image_height', 'setting-default_portfolio_index_image_post_height', '');
		    $this->display_content = themify_get('portfolio_display_content', 'excerpt');
		    $this->posts_per_page = themify_get('portfolio_posts_per_page');
		    $this->page_navigation = themify_get('portfolio_hide_navigation', 'no');
		    $this->order = themify_get('portfolio_order','desc');
		    $this->orderby = themify_get('portfolio_orderby','date');
		    $this->post_filter = themify_get('portfolio_disable_filter');
		    if ($this->orderby === 'meta_value' || $this->orderby === 'meta_value_num') {
			$this->order_meta_key = themify_get('portfolio_meta_key', '');
		    }

		}
		elseif ($post_query_category !== '') {

		    // GENERAL QUERY POSTS
		    $this->query_category = $post_query_category;
		    $this->query_taxonomy = 'category';
		    $this->query_post_type = 'post';
		    $this->post_layout = themify_get('layout','auto_tiles');
		    $this->hide_title = themify_get('hide_title', $this->hide_title);
		    $this->unlink_title = themify_get('unlink_title', $this->unlink_title);
		    $this->hide_image = themify_get('hide_image', $this->hide_image);
		    $this->unlink_image = themify_get('unlink_image', $this->unlink_image);
		    $this->hide_date = themify_get('hide_date', $this->hide_date);
		    $this->display_content = themify_get('display_content', 'excerpt');

		    $this->page_navigation = themify_get('hide_navigation', $this->page_navigation);
		    $this->posts_per_page = themify_get('posts_per_page', $this->posts_per_page);
		    $this->media_position = themify_get('media_position', $this->media_position);
		    $this->width = themify_get('image_width', $this->width);
		    $this->height = themify_get('image_height', $this->height);
		    $this->post_filter = themify_get('disable_filter');

		    $this->order = themify_get('order', 'desc');
		    $this->orderby = themify_get('orderby', 'date');

		    if ($this->orderby === 'meta_value' || $this->orderby === 'meta_value_num') {
			$this->order_meta_key = themify_get('meta_key', $this->order_meta_key);
		    }

		    // Post Meta Values ///////////////////////
		    $post_meta_keys = array(
			'_author' => 'post_meta_author',
			'_category' => 'post_meta_category',
			'_comment' => 'post_meta_comment',
			'_tag' => 'post_meta_tag'
		    );
		    $post_meta_key = 'setting-default_';
		    $this->hide_meta = themify_get($post_meta_key . 'post_meta', $this->hide_meta);
		    foreach ($post_meta_keys as $k => $v) {
			$this->{'hide_meta' . $k} = themify_get_both('hide_meta' . $k, $post_meta_key . $v);
		    }
		}
	    }
	} 
	elseif (is_post_type_archive('portfolio') || is_tax('portfolio-category')) {
	    $this->layout = themify_get('setting-default_portfolio_index_layout', 'sidebar-none', true);
	    $this->post_layout = themify_get('setting-default_portfolio_index_post_layout', 'grid3', true);
	    $this->post_layout_type = themify_get('setting-portfolio_content_layout', '', true);
	    $this->display_content = themify_get('setting-default_portfolio_index_display', 'none', true);
	    $this->hide_title = themify_get('setting-default_portfolio_index_title', 'no', true);
	    $this->unlink_title = themify_get('setting-default_portfolio_index_unlink_post_title', 'no', true);
	    $this->unlink_image =themify_get('setting-default_portfolio_index_unlink_post_image', 'no', true);
	    $this->hide_meta = themify_get('setting-default_portfolio_index_post_meta_category', 'yes', true);
	    $this->hide_date = themify_get('setting-default_portfolio_index_post_date', 'yes', true);
	    $this->width = themify_get('setting-default_portfolio_index_image_post_width', '', true);
	    $this->height = themify_get('setting-default_portfolio_index_image_post_height', '', true);
	    $this->hide_image='';
	} 
	elseif (is_single()) {

	    $this->media_position = 'above';
	    $this->display_content = '';

	    if (is_singular('portfolio')) {
		$this->layout = themify_get_both('layout', 'setting-default_portfolio_single_layout', 'sidebar-none');
		$this->post_layout_type = themify_get_both('post_layout', 'setting-default_portfolio_single_portfolio_layout_type');
		$this->hide_meta = themify_get_both('hide_post_meta', 'setting-default_portfolio_single_post_meta_category', 'no');
		$this->hide_date = themify_get_both('hide_post_date', 'setting-default_portfolio_single_post_date', 'no');
		$this->hide_title = themify_get_both('hide_post_title', 'setting-default_portfolio_single_title');
		$this->unlink_title = themify_get_both('unlink_post_title', 'setting-default_portfolio_single_unlink_post_title');
		$this->unlink_image = themify_get_both('unlink_post_image', 'setting-default_portfolio_single_unlink_post_image');
		$this->width = themify_get_both('image_width', 'setting-default_portfolio_single_image_post_width', '');
		$this->height = themify_get_both('image_height', 'setting-default_portfolio_single_image_post_height', '');
		$this->hide_image = themify_get( 'hide_post_image');
	    } else {
		$this->layout = themify_get_both('layout', 'setting-default_page_post_layout', 'sidebar1');
		$this->post_layout_type = themify_get_both('post_layout', 'setting-default_page_post_layout_type');
		$this->hide_title = themify_get_both('hide_post_title', 'setting-default_page_post_title');
		$this->unlink_title = themify_get_both('unlink_post_title', 'setting-default_page_unlink_post_title');
		$this->hide_date = themify_get_both('hide_post_date', 'setting-default_page_post_date');
		$this->hide_image = themify_get_both('hide_post_image', 'setting-default_page_post_image');
		$this->unlink_image = themify_get_both('unlink_post_image', 'setting-default_page_unlink_post_image');
		$this->width = themify_get_both('image_width', 'setting-image_post_single_width', '');
		$this->height = themify_get_both('image_height', 'setting-image_post_single_height', '');
		$this->inline_date = $this->hide_date !== 'yes' && themify_get('setting-default_page_display_date_inline', '', true);

		// Post Meta Values ///////////////////////
		$post_meta_keys = array(
		    '_author' => 'post_meta_author',
		    '_category' => 'post_meta_category',
		    '_comment' => 'post_meta_comment',
		    '_tag' => 'post_meta_tag'
		);

		$post_meta_key = 'setting-default_page_';
		$this->hide_meta = themify_get_both('hide_meta_all', $post_meta_key . 'post_meta');
		foreach ($post_meta_keys as $k => $v) {
		    $this->{'hide_meta' . $k} = themify_get_both('hide_meta' . $k, $post_meta_key . $v);
		}
	    }
	} elseif (is_archive()) {
	    $excluded_types = apply_filters('themify_exclude_CPT_for_sidebar', array('post', 'page', 'attachment', 'tbuilder_layout', 'tbuilder_layout_part', 'section'));
	    $postType = get_post_type();
	    if (!in_array($postType, $excluded_types, true)) {
		$this->layout = themify_get('setting-custom_post_' . $postType . '_archive', $this->layout, true);
	    }
	}
	if ($this->post_layout_type === 'split') {
	    $this->layout = 'sidebar-none';
	}
	if($this->post_layout==='custom_tiles'){
	  $this->post_layout='auto_tiles';
	}
	
	
	if ($this->width === '' && $this->height === '') {
	    if (is_single()) {
		$this->width = is_singular('portfolio') ? self::$single_portfolio_image_width : self::$single_image_width;
		$this->height = is_singular('portfolio') ? self::$single_portfolio_image_height : self::$single_image_height;
	    } else {
		switch ($this->post_layout) {
		    case 'grid4':
			$this->width = self::$grid4_width;
			$this->height = self::$grid4_height;
			break;
		    case 'grid3':
			$this->width = self::$grid3_width;
			$this->height = self::$grid3_height;
			break;
		    case 'grid2':
			$this->width = self::$grid2_width;
			$this->height = self::$grid2_height;
			break;
		    case 'list-large-image':
			$this->width = self::$list_large_image_width;
			$this->height = self::$list_large_image_height;
			break;
		    case 'list-thumb-image':
			$this->width = self::$list_thumb_image_width;
			$this->height = self::$list_thumb_image_height;
			break;
		    case 'grid2-thumb':
			$this->width = self::$grid2_thumb_width;
			$this->height = self::$grid2_thumb_height;
			break;
		    default :
			$this->width = self::$list_post_width;
			$this->height = self::$list_post_height;
			break;
		}
	    }
	}
    }

}

global $themify;
$themify = new Themify();
