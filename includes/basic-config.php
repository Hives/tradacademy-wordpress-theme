<?php

// USE THIS DOC FOR BASIC CONFIG AND FIXES TO DEFAULT WORDPRESS BEHAVIOUR

// this is necessary for the calendar to process daylight savings time correctly
date_default_timezone_set('Europe/London');

/**
*   -----------------SOME BASIC CONFIG----------------------
*/

// set image sizes
add_theme_support( 'post-thumbnails' );
add_image_size( "small", 480, 480, false );
// update_option('medium_size_w', 326);
// update_option('small_size_w', 480);
// update_option('small_size_w', 0);
update_option('medium_size_w', 780);
update_option('medium_size_h', 0);
update_option('large_size_w', 1024);
update_option('large_size_h', 0);

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * Good explanation here:
 * https://viastudio.com/optimizing-your-theme-for-wordpress-4-4s-responsive-images/
 *
 */
function tradacademy_content_image_sizes_attr( $sizes, $size ) {
    $width = $size[0];

    return '(min-width: 1170px) 780px, (min-width: 1025px) 67vw, 100vw';

    // return '(max-width: 1024px) 100vw, (max-width: 1170px) 67vw, 780px';

}
add_filter( 'wp_calculate_image_sizes', 'tradacademy_content_image_sizes_attr', 10 , 2 );


// stick this in here...
// http://wordpress.org/support/topic/10px-added-to-width-in-image-captions
class fixImageMargins{
    public $xs = 4; //change this to change the amount of extra spacing

    public function __construct(){
        add_filter('img_caption_shortcode', array(&$this, 'fixme'), 10, 3);
    }
    public function fixme($x=null, $attr, $content){

        extract(shortcode_atts(array(
                'id'    => '',
                'align'    => 'alignnone',
                'width'    => '',
                'caption' => ''
            ), $attr));

        if ( 1 > (int) $width || empty($caption) ) {
            return $content;
        }

        if ( $id ) $id = 'id="' . $id . '" ';

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width + $this->xs) . 'px">'
    . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
    }
}
$fixImageMargins = new fixImageMargins();

// also bung dis here https://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'filter_ptags_on_images');


// set excerpt length
function custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
