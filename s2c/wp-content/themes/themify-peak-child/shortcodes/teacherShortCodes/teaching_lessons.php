<?php

function teachingLessons()
{
    $current_page_id = get_the_ID();
    // WP_Query arguments
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'category_name' => 'lessons',
        'meta_query' => array(
            array(
                'key' => 'teaching',
                'value' => $current_page_id,
                'compare' => 'LIKE',
            ),
        ),
    );

    // The Query
    $lesson_query = new WP_Query($args);

    if ($lesson_query) {
        echo '<ul>';
        while ($lesson_query->have_posts()) {
            $lesson_query->the_post();
            ?>
            <li class="teacher_desc">
                <?php echo '<a href="' . esc_url(get_permalink()) . '" target="_blank">' . esc_html(get_field('title')) . '</a>'; ?>
            </li>
            <?php
        }
        wp_reset_postdata();
        echo '</ul>';

    } else {
        echo 'lesson_query no';
    }
}

// Register the shortcode
add_shortcode('lessons_teaching', 'teachingLessons');
