<?php

function custom_child_theme_scripts()
{
	wp_enqueue_script('themify-child-theme-js', get_stylesheet_directory_uri() . '/child-theme-scripts.js', ['jquery'], '1.0', true);
}
add_action('wp_enqueue_scripts', 'custom_child_theme_scripts');




//custom short codes for Teacher pages
include('shortcodes/teacherShortCodes/teacher_fancy_header.php');
include('shortcodes/teacherShortCodes/teacher_bio.php');
include('shortcodes/teacherShortCodes/teaching_lessons.php');

//custom short codes for calendar view
include('shortcodes/calendarViewShortCodes/show_selection.php');


//custom short codes for Lessons pages
include('shortcodes/LessonsShortCodes/gc-event-view.php');
include('shortcodes/LessonsShortCodes/who_teaching.php');
include('shortcodes/LessonsShortCodes/show_prerequisites.php');
include('shortcodes/LessonsShortCodes/lesson_Heading.php');


function enqueue_child_theme_styles()
{
	wp_enqueue_style('child-theme-style', get_stylesheet_directory_uri() . '/style.css', array('parent-theme-style'), '1.0');
}
add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles');
