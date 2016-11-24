<?php
/**
*   -----------------THEME STYLES AND JS----------------------
*/


function theme_styles() {

    wp_enqueue_style(
        "html5-boilerplate-normalize",
        $src = get_template_directory_uri() . '/css/normalize.css',
        $media = 'all'
    );
    wp_enqueue_style(
        "main",
        $src = get_template_directory_uri() . '/style.css',
        $media = 'all'
    );
    wp_enqueue_style(
        "wp-defaults",
        $src = get_template_directory_uri() . '/css/wordpress-defaults.css',
        $media = 'all'
    );
    wp_enqueue_style(
        "slick",
        $src = get_template_directory_uri() . '/lib/slick/slick/slick.css',
        $media = 'all'
    );
    wp_enqueue_style(
        "slick theme",
        $src = get_template_directory_uri() . '/lib/slick/slick/slick-theme.css',
        $media = 'all'
    );

}
add_action('wp_enqueue_scripts', 'theme_styles');


function theme_scripts() {
    wp_enqueue_script(
        "modernizr",
        $src = get_template_directory_uri() . '/js/vendor/modernizr-2.6.2.min.js',
        $deps = array(),
        $ver = null,
        $in_footer = false
    );
    wp_enqueue_script(
        "jquizzle",
        $src = get_template_directory_uri() . '/js/vendor/jquery-1.9.0.min.js',
        $deps = array(),
        $ver = null,
        $in_footer = true
    );
    wp_enqueue_script(
        "plugins",
        $src = get_template_directory_uri() . '/js/plugins.js',
        $deps = array('jquizzle'),
        $ver = null,
        $in_footer = true
    );
    // wp_enqueue_script(
    //     "google_maps",
    //     $src = 'https://maps.googleapis.com/maps/api/js?sensor=false&region=GB',
    //     $deps = array(),
    //     $ver = null,
    //     $in_footer = true
    // );
    wp_enqueue_script(
        "carousel",
        $src = get_template_directory_uri() . '/lib/slick/slick/slick.min.js',
        $deps = array('jquizzle'),
        $ver = null,
        $in_footer = true
    );
    wp_enqueue_script(
        "main",
        $src = get_template_directory_uri() . '/js/main.js',
        $deps = array('jquizzle', 'plugins', 'carousel'),
        $ver = null,
        $in_footer = true
    );
}
add_action('wp_enqueue_scripts', 'theme_scripts');


/**
*   -----------------ADMIN STYLES AND JS----------------------
*/

function admin_styles() {
    wp_enqueue_style(
        "admin",
        $src = get_template_directory_uri() . '/css/admin.css',
        $deps = array(),
        $ver = null,
        $media = 'all'
    );
    wp_enqueue_style(
        "jquery-timepicker",
        $src = get_template_directory_uri() . '/lib/jquery-timepicker/jquery.timepicker.css',
        $deps = array(),
        $ver = null,
        $media = 'all'
    );
}
add_action('admin_enqueue_scripts', 'admin_styles');

function admin_scripts() {
    wp_enqueue_script(
        "plugins",
        $src = get_template_directory_uri() . '/js/plugins.js',
        $deps = array('jquery'),
        $ver = null,
        $in_footer = true
    );
    wp_enqueue_script(
        "google_maps",
        $src = 'https://maps.googleapis.com/maps/api/js?sensor=false&region=GB',
        $deps = array(),
        $ver = null,
        $in_footer = true
    );
    wp_enqueue_script(
        "jquery-ui-datepicker",
        $deps = array("jquery"),
        $ver = null,
        $in_footer = true
    );
    wp_enqueue_script(
        "jquery-timepicker",
        $src = get_template_directory_uri() . '/lib/jquery-timepicker/jquery.timepicker.min.js',
        $deps = array("jquery"),
        $ver = null,
        $in_footer = true
    );
    wp_enqueue_script(
        "datepair",
        $src = get_template_directory_uri() . '/lib/Datepair.js/dist/jquery.datepair.min.js',
        $deps = array("jquery"),
        $ver = null,
        $in_footer = true
    );
    wp_enqueue_script(
        "trad_academy_admin",
        $src = get_template_directory_uri() . '/js/admin.js',
        $deps = array("plugins", "jquery-ui-datepicker", "jquery-timepicker"),
        $ver = null,
        $in_footer = true
    );
}
add_action('admin_enqueue_scripts', 'admin_scripts');
