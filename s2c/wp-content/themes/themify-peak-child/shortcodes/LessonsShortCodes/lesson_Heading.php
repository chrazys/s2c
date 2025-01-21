<?php
function lesson_header()
{
    $teachers = get_field('teaching');
    $name = get_field('title');
    $gc_id = get_field('gc_id');
    $teacher_names = array();
    if (is_array($teachers)) {
        foreach ($teachers as $teacher_id) {
            $teacher_names[] = '<a class="red_p" href="' . get_permalink($teacher_id) . '" target="_blank">' . get_field('name', $teacher_id) . '</a>';
        }
    }

    ?>
    <div class="custom-fancy-heading">
        <h3 class="custom-fancy-h3 ">
            <span class="custom-fancy-main-head tf_block">
                <?php echo $name ?>
            </span>
            <span class="custom-fancy-sub-head tf_block">
                <?php if (is_array($teachers) && count($teachers) > 0) {
                    ?>
                    Διδάσκει:&nbsp
                    <?php
                    echo implode(',&nbsp', $teacher_names);
                } ?>

            </span>
            <?php
            if ($gc_id !== '') {
                ?>
                <span class="custom-fancy-sub-head tf_block">
                    <a href="http://s2c/?page_id=3661&calendar_ID=<?php echo $gc_id; ?>" target="_blank">
                        Ημερολόγιο
                    </a>
                </span>
                <?php
            }
            ?>

        </h3>
    </div>
    <?php

}

// Register the shortcode
add_shortcode('lesson_header', 'lesson_header');
