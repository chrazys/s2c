<?php
function no_prerequisites()
{
    ?>
    <div class="no_prerequisites">
        <span class="no_prerequisites_p">Απευθύνεται σε όλους όσους θέλουν να ταξιδέψουν στον κόσμο του θεάτρου
            ανεξαρτήτως
            προηγούμενης θεατρικής
            εμπειρίας.</span>
    </div>
    <?php
}
function show_prerequisites($atts)
{
    $lessons = get_field('prerequisites');
    if ($lessons == "") {
        no_prerequisites();
    } else {
        $filtered_lessons = array_diff($lessons, array(get_the_ID()));

        if (empty($filtered_lessons)) {
            no_prerequisites();
        } else {

            $lessons_names = array();
            if (is_array($filtered_lessons)) {
                foreach ($filtered_lessons as $lesson) {
                    if ($lesson !== '') {
                        $lessons_names[] = '<a class="red_p" href="' . get_permalink($lesson) . '" target="_blank">' . get_field('title', $lesson) . '</a>';
                    }
                }
            }

            ?>
            <div class="custom-fancy-heading">
                <?php
                echo implode(',&nbsp', $lessons_names);
                ?>
            </div>
            <?php


        }
    }
}

add_shortcode('custom_prerequisites', 'show_prerequisites');