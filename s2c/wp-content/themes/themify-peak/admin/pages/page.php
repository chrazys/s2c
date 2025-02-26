<?php

/**
 * Page Meta Box Options
 * @var array Options for Themify Custom Panel
 * @since 1.0.0
 */
if (!function_exists('themify_theme_page_meta_box')) {

    function themify_theme_page_meta_box() {
	return array(
	    // Page Sub Heading
	    array(
		'name' => 'page_sub_heading',
		'title' => __('Page Sub Heading', 'themify'),
		'description' => '',
		'type' => 'textarea'
	    ),
	    // Page Layout
	    array(
		'name' => 'page_layout',
		'title' => __('Sidebar Option', 'themify'),
		'description' => '',
		'type' => 'layout',
		'show_title' => true,
		'meta' => array(
		    array('value' => 'default', 'img' => 'themify/img/default.svg', 'selected' => true, 'title' => __('Default', 'themify')),
		    array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
		    array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
		    array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar ', 'themify'))
		),
		'default' => 'default'
	    ),
	    // Content Width
	    array(
		'name' => 'content_width',
		'title' => __('Content Width', 'themify'),
		'description' => 'Select "Fullwidth" if the page is to be built with the Builder without the sidebar (it will make the Builder content fullwidth).',
		'type' => 'layout',
		'show_title' => true,
		'meta' => array(
		    array(
			'value' => 'default_width',
			'img' => 'themify/img/default.svg',
			'selected' => true,
			'title' => __('Default', 'themify')
		    ),
		    array(
			'value' => 'full_width',
			'img' => 'themify/img/fullwidth.svg',
			'title' => __('Fullwidth (Builder Page)', 'themify')
		    )
		),
		'default' => 'default_width'
	    ),
	    // Hide page title
	    array(
		'name' => 'hide_page_title',
		'title' => __('Hide Page Title', 'themify'),
		'description' => '',
		'type' => 'dropdown',
		'meta' => array(
		    array('value' => 'default', 'name' => '', 'selected' => true),
		    array('value' => 'yes', 'name' => __('Yes', 'themify')),
		    array('value' => 'no', 'name' => __('No', 'themify'))
		),
		'default' => 'default'
	    ),
	    // Custom menu for page
	    array(
		'name' => 'custom_menu',
		'title' => __('Custom Menu', 'themify'),
		'description' => '',
		'type' => 'dropdown',
		'meta' => themify_get_available_menus()
	    )
	);
    }

}

// Query Post Meta Box Options
function themify_theme_query_post_meta_box() {
    return array(
	// Notice
	array(
	    'name' => '_query_posts_notice',
	    'title' => '',
	    'description' => '',
	    'type' => 'separator',
	    'meta' => array(
		'html' => '<div class="themify-info-link">' . sprintf(__('<a href="%s">Query Posts</a> allows you to query WordPress posts from any category on the page. To use it, select a Query Category.', 'themify'), 'https://themify.me/docs/query-posts') . '</div>'
	    ),
	),
	// Query Category
	array(
	    'name' => 'query_category',
	    'title' => __('Query Category', 'themify'),
	    'description' => __('Select a category or enter multiple category IDs (eg. 2,5,6). Enter 0 to display all category.', 'themify'),
	    'type' => 'query_category',
	    'meta' => array()
	),
	// Query All Post Types
	array(
	    'name' => 'query_all_post_types',
	    'type' => 'dropdown',
	    'title' => __('Query All Post Types', 'themify'),
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		),
		array(
		    'value' => 'yes',
		    'name' => 'Yes',
		),
		array(
		    'value' => 'no',
		    'name' => 'No',
		),
	    )
	),
	// Descending or Ascending Order for Posts
	array(
	    'name' => 'order',
	    'title' => __('Order', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('name' => __('Descending', 'themify'), 'value' => 'desc', 'selected' => true),
		array('name' => __('Ascending', 'themify'), 'value' => 'asc')
	    ),
	    'default' => 'desc'
	),
	// Criteria to Order By
	array(
	    'name' => 'orderby',
	    'title' => __('Order By', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('name' => __('Date', 'themify'), 'value' => 'date', 'selected' => true),
		array('name' => __('Random', 'themify'), 'value' => 'rand'),
		array('name' => __('Author', 'themify'), 'value' => 'author'),
		array('name' => __('Post Title', 'themify'), 'value' => 'title'),
		array('name' => __('Comments Number', 'themify'), 'value' => 'comment_count'),
		array('name' => __('Modified Date', 'themify'), 'value' => 'modified'),
		array('name' => __('Post Slug', 'themify'), 'value' => 'name'),
		array('name' => __('Post ID', 'themify'), 'value' => 'ID'),
		array('name' => __('Custom Field String', 'themify'), 'value' => 'meta_value'),
		array('name' => __('Custom Field Numeric', 'themify'), 'value' => 'meta_value_num')
	    ),
	    'default' => 'date',
	    'hide' => 'date|rand|author|title|comment_count|modified|name|ID field-meta-key'
	),
	array(
	    'name' => 'meta_key',
	    'title' => __('Custom Field Key', 'themify'),
	    'description' => '',
	    'type' => 'textbox',
	    'meta' => array('size' => 'medium'),
	    'class' => 'field-meta-key'
	),
	// Post Layout
	array(
	    'name' => 'layout',
	    'title' => __('Query Post Layout', 'themify'),
	    'description' => '',
	    'type' => 'layout',
	    'show_title' => true,
	    'meta' => array(
		array('value' => 'auto_tiles', 'img' => 'images/layout-icons/auto-tiles.png', 'title' => __('Tiles', 'themify'), 'selected' => true),
		array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'title' => __('List Post', 'themify')),
		array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'title' => __('Grid 4', 'themify')),
		array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
		array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify')),
	    ),
	    'default' => 'auto_tiles'
	),
	// Post Filter
	array(
	    'name' => 'disable_filter',
	    'title' => __('Post Filter', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'yes',
		    'name' => __('Enable', 'themify'),
		),
		array(
		    'value' => 'no',
		    'name' => __('Disable', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Portfolio Content Layout
	array(
	    'name' => 'post_content_layout',
	    'title' => __('Post Content Layout', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'overlay',
		    'name' => __('Overlay', 'themify'),
		),
		array(
		    'value' => 'polaroid',
		    'name' => __('Polaroid', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Post Masonry
	array(
	    'name' => 'disable_masonry',
	    'title' => __('Post Masonry', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'yes',
		    'name' => __('Enable', 'themify'),
		),
		array(
		    'value' => 'no',
		    'name' => __('Disable', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Post Gutter
	array(
	    'name' => 'post_gutter',
	    'title' => __('Post Gutter', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'no-gutter',
		    'name' => __('No gutter', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Infinite Scroll
	array(
	    'name' => 'more_posts',
	    'title' => __('Infinite Scroll', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'infinite',
		    'name' => __('Enable', 'themify'),
		),
		array(
		    'value' => 'pagination',
		    'name' => __('Disable', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Posts Per Page
	array(
	    'name' => 'posts_per_page',
	    'title' => __('Posts Per Page', 'themify'),
	    'description' => '',
	    'type' => 'textbox',
	    'meta' => array('size' => 'small')
	),
	// Display Content
	array(
	    'name' => 'display_content',
	    'title' => __('Display Content', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('name' => __('Full Content', 'themify'), 'value' => 'content'),
		array('name' => __('Excerpt', 'themify'), 'value' => 'excerpt', 'selected' => true),
		array('name' => __('None', 'themify'), 'value' => 'none')
	    ),
	    'default' => 'excerpt'
	),
	// Featured Image Size
	array(
	    'name' => 'feature_size_page',
	    'title' => __('Image Size', 'themify'),
	    'description' => sprintf(__('Image sizes can be set at <a href="%s">Media Settings</a> and <a href="%s">Regenerated</a>', 'themify'), 'options-media.php', 'admin.php?page=regenerate-thumbnails'),
	    'type' => 'featimgdropdown',
	    'display_callback' => 'themify_is_image_script_disabled'
	),
	// Multi field: Image Dimension
	themify_image_dimensions_field(),
	// Hide Title
	array(
	    'name' => 'hide_title',
	    'title' => __('Hide Post Title', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Unlink Post Title
	array(
	    'name' => 'unlink_title',
	    'title' => __('Unlink Post Title', 'themify'),
	    'description' => __('Unlink post title (it will display the post title without link)', 'themify'),
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Hide Post Date
	array(
	    'name' => 'hide_date',
	    'title' => __('Hide Post Date', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Multi field: Hide Post Meta
	themify_multi_meta_field(),
	// Media Above/Below Title
	array(
	    'name' => 'media_position',
	    'title' => __('Media Position', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'below', 'name' => __('Below Post Title', 'themify')),
		array('value' => 'above', 'name' => __('Above Post Title', 'themify')),
	    ),
	    'default' => 'default'
	),
	// Hide Post Image
	array(
	    'name' => 'hide_image',
	    'title' => __('Hide Featured Image', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Unlink Post Image
	array(
	    'name' => 'unlink_image',
	    'title' => __('Unlink Featured Image', 'themify'),
	    'description' => __('Display the Featured Image without link', 'themify'),
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Pagination Visibility
	array(
	    'name' => 'hide_navigation',
	    'title' => __('Hide Pagination', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	)
    );
}

/**
 * Query Portfolio Meta Box Options
 * @param array $args
 * @return array
 * @since 1.0.7
 */
function themify_theme_query_portfolio_meta_box($args = array()) {
    extract($args);
    return array(
	// Notice
	array(
	    'name' => '_query_portfolio_notice',
	    'title' => '',
	    'description' => '',
	    'type' => 'separator',
	    'meta' => array(
		'html' => '<div class="themify-info-link">' . sprintf(__('<a href="%s">Query Portfolios</a> allows you to query WordPress portfolios from any portfolio category. To use it, select a Portfolio Category.', 'themify'), 'https://themify.me/docs/query-posts') . '</div>'
	    ),
	),
	// Query Category
	array(
	    'name' => 'portfolio_query_category',
	    'title' => __('Portfolio Category', 'themify'),
	    'description' => __('Select a portfolio category or enter multiple portfolio category IDs (eg. 2,5,6). Enter 0 to display all portfolio categories.', 'themify'),
	    'type' => 'query_category',
	    'meta' => array('taxonomy' => 'portfolio-category')
	),
	// Descending or Ascending Order for Portfolios
	array(
	    'name' => 'portfolio_order',
	    'title' => __('Order', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('name' => __('Descending', 'themify'), 'value' => 'desc', 'selected' => true),
		array('name' => __('Ascending', 'themify'), 'value' => 'asc')
	    ),
	    'default' => 'desc'
	),
	// Criteria to Order By
	array(
	    'name' => 'portfolio_orderby',
	    'title' => __('Order By', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('name' => __('Date', 'themify'), 'value' => 'date', 'selected' => true),
		array('name' => __('Random', 'themify'), 'value' => 'rand'),
		array('name' => __('Author', 'themify'), 'value' => 'author'),
		array('name' => __('Post Title', 'themify'), 'value' => 'title'),
		array('name' => __('Comments Number', 'themify'), 'value' => 'comment_count'),
		array('name' => __('Modified Date', 'themify'), 'value' => 'modified'),
		array('name' => __('Post Slug', 'themify'), 'value' => 'name'),
		array('name' => __('Post ID', 'themify'), 'value' => 'ID'),
		array('name' => __('Custom Field String', 'themify'), 'value' => 'meta_value'),
		array('name' => __('Custom Field Numeric', 'themify'), 'value' => 'meta_value_num')
	    ),
	    'default' => 'date',
	    'hide' => 'date|rand|author|title|comment_count|modified|name|ID field-portfolio-meta-key'
	),
	array(
	    'name' => 'portfolio_meta_key',
	    'title' => __('Custom Field Key', 'themify'),
	    'description' => '',
	    'type' => 'textbox',
	    'meta' => array('size' => 'medium'),
	    'class' => 'field-portfolio-meta-key'
	),
	// Post Layout
	array(
	    'name' => 'portfolio_layout',
	    'title' => __('Portfolio Layout', 'themify'),
	    'description' => '',
	    'type' => 'layout',
	    'show_title' => true,
	    'meta' => array(
		array('value' => 'auto_tiles', 'img' => 'images/layout-icons/auto-tiles.png', 'title' => __('Tiles', 'themify'), 'selected' => true),
		array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'title' => __('List Post', 'themify')),
		array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'title' => __('Grid 4', 'themify')),
		array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
		array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify')),
	    ),
	    'default' => 'auto_tiles'
	),
	// Post Filter
	array(
	    'name' => 'portfolio_disable_filter',
	    'title' => __('Portfolio Filter', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'yes',
		    'name' => __('Enable', 'themify'),
		),
		array(
		    'value' => 'no',
		    'name' => __('Disable', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Post Content Layout
	array(
	    'name' => 'portfolio_content_layout',
	    'title' => __('Portfolio Content Layout', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'overlay',
		    'name' => __('Overlay', 'themify'),
		),
		array(
		    'value' => 'polaroid',
		    'name' => __('Polaroid', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Post Masonry
	array(
	    'name' => 'portfolio_disable_masonry',
	    'title' => __('Masonry Layout', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'yes',
		    'name' => __('Enable', 'themify'),
		),
		array(
		    'value' => 'no',
		    'name' => __('Disable', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Post Gutter
	array(
	    'name' => 'portfolio_gutter',
	    'title' => __('Portfolio Gutter', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'no-gutter',
		    'name' => __('No gutter', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Infinite Scroll
	array(
	    'name' => 'portfolio_more_posts',
	    'title' => __('Infinite Scroll', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array(
		    'value' => '',
		    'name' => '',
		    'selected' => true,
		),
		array(
		    'value' => 'infinite',
		    'name' => __('Enable', 'themify'),
		),
		array(
		    'value' => 'pagination',
		    'name' => __('Disable', 'themify'),
		)
	    ),
	    'default' => ''
	),
	// Posts Per Page
	array(
	    'name' => 'portfolio_posts_per_page',
	    'title' => __('Portfolios Per Page', 'themify'),
	    'description' => '',
	    'type' => 'textbox',
	    'meta' => array('size' => 'small')
	),
	// Display Content
	array(
	    'name' => 'portfolio_display_content',
	    'title' => __('Display Content', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('name' => __('Full Content', 'themify'), 'value' => 'content'),
		array('name' => __('Excerpt', 'themify'), 'value' => 'excerpt', 'selected' => true),
		array('name' => __('None', 'themify'), 'value' => 'none')
	    ),
	    'default' => 'excerpt'
	),
	// Featured Image Size
	array(
	    'name' => 'portfolio_feature_size_page',
	    'title' => __('Image Size', 'themify'),
	    'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
	    'type' => 'featimgdropdown',
	    'display_callback' => 'themify_is_image_script_disabled'
	),
	// Multi field: Image Dimension
	themify_image_dimensions_field(array(), 'portfolio_image'),
	// Hide Title
	array(
	    'name' => 'portfolio_hide_title',
	    'title' => __('Hide Portfolio Title', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => ''
	),
	// Unlink Post Title
	array(
	    'name' => 'portfolio_unlink_title',
	    'title' => __('Unlink Portfolio Title', 'themify'),
	    'description' => __('Unlink portfolio title (it will display the post title without link)', 'themify'),
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Hide Post Meta
	array(
	    'name' => 'portfolio_hide_meta_all',
	    'title' => __('Hide Portfolio Meta', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Hide Post Image
	array(
	    'name' => 'portfolio_hide_image',
	    'title' => __('Hide Featured Image', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Unlink Post Image
	array(
	    'name' => 'portfolio_unlink_image',
	    'title' => __('Unlink Featured Image', 'themify'),
	    'description' => __('Display the Featured Image Without Link', 'themify'),
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	),
	// Pagination Visibility
	array(
	    'name' => 'portfolio_hide_navigation',
	    'title' => __('Hide Pagination', 'themify'),
	    'description' => '',
	    'type' => 'dropdown',
	    'meta' => array(
		array('value' => 'default', 'name' => '', 'selected' => true),
		array('value' => 'yes', 'name' => __('Yes', 'themify')),
		array('value' => 'no', 'name' => __('No', 'themify'))
	    ),
	    'default' => 'default'
	)
    );
}

/**
 * Default Page Layout Module
 * @param array $data Theme settings data
 * @return string Markup for module.
 * @since 1.0.0
 */
function themify_default_page_layout($data = array()) {
    $data = themify_get_data();

    /**
     * Theme Settings Option Key Prefix
     * @var string
     */
    $prefix = 'setting-default_page_';

    /**
     * Sidebar placement options
     * @var array
     */
    $sidebar_location_options = array(
	array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true, 'title' => __('Sidebar Right', 'themify')),
	array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
	array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify'))
    );

    /**
     * Tertiary options <blank>|yes|no
     * @var array
     */
    $default_options = array(
	array('name' => '', 'value' => ''),
	array('name' => __('Yes', 'themify'), 'value' => 'yes'),
	array('name' => __('No', 'themify'), 'value' => 'no')
    );

    /**
     * Module markup
     * @var string
     */
    $output = '';

    /**
     * Page sidebar placement
     */
    $output .= '<p>
					<span class="label">' . __('Page Sidebar Option', 'themify') . '</span>';
    $val = themify_get($prefix . 'layout');
    foreach ($sidebar_location_options as $option) {
	if (( '' == $val || !$val || !isset($val) ) && ( isset($option['selected']) && $option['selected'] )) {
	    $val = $option['value'];
	}
	if ($val == $option['value']) {
	    $class = 'selected';
	} else {
	    $class = '';
	}
	$output .= '<a href="#" class="preview-icon ' . $class . '" title="' . $option['title'] . '"><img src="' . THEME_URI . '/' . $option['img'] . '" alt="' . $option['value'] . '"  /></a>';
    }
    $output .= '<input type="hidden" name="' . $prefix . 'layout" class="val" value="' . $val . '" /></p>';

    /**
     * Hide Title in All Pages
     */
    $output .= '<p>
					<span class="label">' . __('Hide Title in All Pages', 'themify') . '</span>
					<select name="setting-hide_page_title">' .
	    themify_options_module($default_options, 'setting-hide_page_title') . '
					</select>
				</p>';

    /**
     * Featured Image dimensions
     */
    $output .= '<p>
				<span class="label">' . __('Image Size', 'themify') . '</span>
				<input type="text" class="width2" name="setting-page_featured_image_width" value="' . themify_get('setting-page_featured_image_width') . '" /> ' . __('width', 'themify') . ' <small>(px)</small>
				<input type="text" class="width2 show_if_enabled_img_php" name="setting-page_featured_image_height" value="' . themify_get('setting-page_featured_image_height') . '" /> <span class="show_if_enabled_img_php">' . __('height', 'themify') . ' <small>(px)</small></span>
				<br /><span class="pushlabel show_if_enabled_img_php"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>
			</p>';

    /**
     * Page Comments
     */
    $pre = 'setting-comments_pages';
    $pages_checked = themify_check($pre) ? 'checked="checked"' : '';

    $output .= '<p><span class="label">' . __('Page Comments', 'themify') . '</span><label for="' . $pre . '"><input type="checkbox" id="' . $pre . '" name="' . $pre . '" ' . $pages_checked . ' /> ' . __('Disable comments in all Pages', 'themify') . '</label></p>';

    return $output;
}

if (!function_exists('themify_theme_get_page_metaboxes')) {

    function themify_theme_get_page_metaboxes(array $args, &$meta_boxes) {
	return array(
	    array(
		'name' => __('Page Options', 'themify'),
		'id' => 'page-options',
		'options' => themify_theme_page_meta_box(),
		'pages' => 'page'
	    ),
	    array(
		'name' => __('Page Appearance', 'themify'),
		'id' => 'page-appearance',
		'options' => themify_theme_appearance_meta_box(),
		'pages' => 'page'
	    ),
	    array(
		'name' => __('Query Posts', 'themify'),
		'id' => 'query-posts',
		'options' => themify_theme_query_post_meta_box(),
		'pages' => 'page'
	    ),
	    array(
		'name' => __('Query Portfolios', 'themify'),
		'id' => 'query-portfolio',
		'options' => themify_theme_query_portfolio_meta_box(),
		'pages' => 'page'
	    )
	);
    }

}
