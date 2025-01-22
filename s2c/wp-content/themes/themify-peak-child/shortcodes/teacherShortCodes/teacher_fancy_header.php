<?php
function fancy_header_shortcode()
{
    $name = get_field('name');
    $specialty = get_field('specialty');
    $icon = get_field('icon');
    $short_bio = get_field('short_bio');

    ?>
    <div class="custom-fancy-heading">
        <?php if ($icon !== ""): ?>
            <div>
                <?php echo $icon ?>
            </div>
        <?php endif; ?>
        <h3 class="custom-fancy-h3 ">
            <span class="custom-fancy-main-head tf_block">
                <?php echo $name ?>
            </span>
            <span class="custom-fancy-sub-head tf_block">
                <?php echo $specialty ?>
            </span>
        </h3>
        <?php
        if ($short_bio !== ""): ?>
            <div>
                <p class="teacher_desc">
                    <?php echo $short_bio ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <?php

}

// Register the shortcode
add_shortcode('single_teacher_header', 'fancy_header_shortcode');
