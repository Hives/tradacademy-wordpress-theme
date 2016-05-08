<?php

// this is necessary for the calendar to process daylight savings time correctly
date_default_timezone_set('Europe/London');

/**
*   -----------------SOME BASIC CONFIG----------------------
*/

// set image sizes
add_theme_support( 'post-thumbnails' );
add_image_size( "carousel-full", 1000, 350, true );
update_option('medium_size_w', 326);
update_option('medium_size_h', 0);
update_option('large_size_w', 676);
update_option('large_size_h', 0);

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


// set excerpt length
function custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
