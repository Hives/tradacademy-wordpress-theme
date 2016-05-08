<?php
/**
*   -----------------CUSTOM POST TYPES----------------------
*/

// add excerpt to pages
add_post_type_support( 'page', 'excerpt' );

// create new post types
add_action( 'init', 'create_new_post_types' );
function create_new_post_types() {

    // course
    $labels_course = array(
        'name' => _x('Courses', 'post type general name'),
        'singular_name' => _x('Course', 'post type singular name'),
        'add_new' => _x('Add New', 'Course'),
        'add_new_item' => __("Add New Course"),
        'edit_item' => __("Edit Course"),
        'new_item' => __("New Course"),
        'view_item' => __("View Course"),
        'search_items' => __("Search Courses"),
        'not_found' =>  __('No courses found'),
        'not_found_in_trash' => __('No courses found in Trash'),
        'parent_item_colon' => ''
    );
    $args_course = array(
        'labels' => $labels_course,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor', 'thumbnail')
    );
    register_post_type('course', $args_course);

    // location
    $labels_location = array(
        'name' => _x('Locations', 'post type general name'),
        'singular_name' => _x('Location', 'post type singular name'),
        'add_new' => _x('Add New', 'Location'),
        'add_new_item' => __("Add New Location"),
        'edit_item' => __("Edit Location"),
        'new_item' => __("New Location"),
        'view_item' => __("View Location"),
        'search_items' => __("Search Locations"),
        'not_found' =>  __('No locations found'),
        'not_found_in_trash' => __('No locations found in Trash'),
        'parent_item_colon' => ''
    );
    $args_location = array(
        'labels' => $labels_location,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );
    register_post_type('location', $args_location);

    // tutor
    $labels_tutor = array(
        'name' => _x('Tutors', 'post type general name'),
        'singular_name' => _x('Tutor', 'post type singular name'),
        'add_new' => _x('Add New', 'Tutor'),
        'add_new_item' => __("Add New Tutor"),
        'edit_item' => __("Edit Tutor"),
        'new_item' => __("New Tutor"),
        'view_item' => __("View Tutor"),
        'search_items' => __("Search Tutors"),
        'not_found' =>  __('No tutors found'),
        'not_found_in_trash' => __('No tutors found in Trash'),
        'parent_item_colon' => ''
    );
    $args_tutor = array(
        'labels' => $labels_tutor,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor', 'thumbnail')
    );
    register_post_type('tutor', $args_tutor);

    // carousel_page
    $labels_carousel_page = array(
        'name' => _x('Carousel Pages', 'post type general name'),
        'singular_name' => _x('Carousel Page', 'post type singular name'),
        'add_new' => _x('Add New', 'Carousel Page'),
        'add_new_item' => __("Add New Carousel Page"),
        'edit_item' => __("Edit Carousel Page"),
        'new_item' => __("New Carousel Page"),
        'view_item' => __("View Carousel Page"),
        'search_items' => __("Search Carousel Pages"),
        'not_found' =>  __('No Carousel Pages found'),
        'not_found_in_trash' => __('No Carousel Pages found in Trash'),
        'parent_item_colon' => ''
    );
    $args_carousel_page = array(
        'labels' => $labels_carousel_page,
        'public' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor', 'thumbnail')
    );
    register_post_type('carousel_page', $args_carousel_page);

}