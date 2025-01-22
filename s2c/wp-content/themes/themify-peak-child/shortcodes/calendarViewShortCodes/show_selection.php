<?php
function listView()
{
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'category_name' => 'lessons',
    );

    $calendarID = isset($_GET['calendar_ID']) ? sanitize_text_field($_GET['calendar_ID']) : '';

    $pages = new WP_Query($args);

    if ($pages->have_posts()) {
        $output = '<div class="page-checkbox-container">';
        $output .= '<input type="text" id="page-filter" placeholder="Filter pages">';
        $output .= '<form id="page-checkbox-form">';
        while ($pages->have_posts()) {
            $pages->the_post();
            $url = get_field('gc_id');
            $name = get_the_title();
            if ($url !== '') {
                $output .= '<label  class="page-checkbox-label"><input class="page-checkbox" ' . ($calendarID === $url && $url !== '' ? ' checked' : '') . ' type="checkbox" name="' . $name . '" value="' . $url . '"> ' . $name . '</label>';
            }
        }
        $output .= '</form>';
        $output .= '</div>';

        wp_reset_postdata();

        return $output;
    } else {
        return 'No pages found in the specified category.';
    }

}

function get_pages_from_category($category_id)
{
    $args = array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'cat' => $category_id,
    );
    return get_pages($args);
}


add_shortcode('category_list', 'listView');



