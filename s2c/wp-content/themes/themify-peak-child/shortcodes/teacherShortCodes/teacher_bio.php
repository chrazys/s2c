<?php

function bio($atts)
{
    $bio = get_field('bio');

    ?>
    <p class="black_p">
        <?php echo $bio ?>
    </p>
    <?php
}

add_shortcode('teacher_bio', 'bio');