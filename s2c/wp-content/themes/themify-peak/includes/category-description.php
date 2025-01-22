<?php

global $themify;
// author bio
if( is_author() ){
    themify_author_bio();
}
else{
    $title= themify_get_title(array('use_date_labels'=>true));
    $sub_heading='';
    if($themify->page_title === 'yes' && is_page()){
	$title = themify_page_title();
	$sub_heading = themify_get('page_sub_heading');
	if($sub_heading){
	    $sub_heading='<div class="category-description">'.$sub_heading.'</div>';
	}
    }
    if(is_category() || is_tag() || is_tax()){
	$sub_heading=themify_get_term_description();
    }
    ?>
    <?php if(!empty($title)):?>
	<div class="page-category-title-wrap">
	    <?php if(is_category() || is_tag() || is_tax() || is_page()):?>
		<div class="category-title-overlay"></div>
	    <?php endif;?>
	    <?php echo $title,$sub_heading; ?>
	    <?php if(is_404()):?>
		<div class="category-description"><?php themify_404_page_content()?></div>
	    <?php endif;?>
	</div>
    <?php endif;?>
    <?php
}
