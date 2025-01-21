<?php

function show_Teachers($atts)
{
    $teachers = get_field('teaching');
    if (!is_array($teachers)) {
        return;
    }
    foreach ($teachers as $teacher_id) {
        $name = get_field('name', $teacher_id);
        $specialty = get_field('specialty', $teacher_id);
        $url = get_permalink($teacher_id);
        $short_bio = get_field('short_bio', $teacher_id);
        $icon = get_field('icon', $teacher_id)

            ?>
        <div class="custom-fancy-heading">
            <a href=<?php echo $url ?>>
                <h3 class="custom-fancy-h3 ">
                    <span class="custom-fancy-main-head tf_block">
                        <?php echo $name ?>
                    </span>
                    <?php if ($specialty !== ""): ?>
                        <span class="custom-fancy-sub-head tf_block">
                            <?php echo $specialty ?>
                        </span>
                    <?php endif; ?>
                </h3>
            </a>
        </div>

        <?php if ($icon !== ""): ?>
            <div>
                <p class="teacher_desc">
                    <?php echo $icon ?>
                </p>
            </div>
        <?php endif;
        if ($short_bio !== ""): ?>
            <div>
                <p class="teacher_desc">
                    <?php echo $short_bio ?>
                </p>
            </div>
        <?php endif;
    }
}

add_shortcode('teachers_display', 'show_Teachers');