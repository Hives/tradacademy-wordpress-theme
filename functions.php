<?php

// this is necessary for the calendar to process daylight savings time correctly
date_default_timezone_set('Europe/London');

/**
*	-----------------SOME BASIC CONFIG----------------------
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



/**
*	-----------------THEMES AND SCRIPTS----------------------
*/

function theme_styles() {
	wp_enqueue_style(
		"html5-boilerplate-normalize",
		$src = get_template_directory_uri() . '/css/normalize.css',
		$deps = array(),
		$ver = null,
		$media = 'all'
	);
	wp_enqueue_style(
		"html5-boilerplate-main",
		$src = get_template_directory_uri() . '/css/main.css',
		$deps = array("html5-boilerplate-normalize"),
		$ver = null,
		$media = 'all'
	);
	wp_enqueue_style(
		"main",
		$src = get_template_directory_uri() . '/style.css',
		$deps = array("html5-boilerplate-main"),
		$ver = null,
		$media = 'all'
	);
	wp_enqueue_style(
		"wp-defaults",
		$src = get_template_directory_uri() . '/css/wordpress-defaults.css',
		$deps = array(),
		$ver = null,
		$media = 'all'
	);
}
add_action('wp_enqueue_scripts', 'theme_styles');

function admin_styles() {
	wp_enqueue_style(
		"admin",
		$src = get_template_directory_uri() . '/css/admin.css',
		$deps = array(),
		$ver = null,
		$media = 'all'
	);
}
add_action('admin_enqueue_scripts', 'admin_styles');


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
	wp_enqueue_script(
		"google_maps",
		$src = 'https://maps.googleapis.com/maps/api/js?sensor=false&region=GB',
		$deps = array(),
		$ver = null,
		$in_footer = true
	);
	wp_enqueue_script(
		"main",
		$src = get_template_directory_uri() . '/js/main.js',
		$deps = array('jquizzle', 'plugins'),
		$ver = null,
		$in_footer = true
	);
	wp_enqueue_script(
		"google-plus-like",
		$src = "https://apis.google.com/js/plusone.js",
		$deps = array(),
		$ver = null,
		$in_footer = true
	);
}
add_action('wp_enqueue_scripts', 'theme_scripts');

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
		// $src = 'https://maps.googleapis.com/maps/api/js?sensor=false&region=GB',
		$deps = array("jquery"),
		$ver = null,
		$in_footer = true
	);
	wp_enqueue_script(
		"trad_academy_admin",
		$src = get_template_directory_uri() . '/js/admin.js',
		$deps = array("plugins", "jquery-ui-datepicker"),
		$ver = null,
		$in_footer = true
	);
}
add_action('admin_enqueue_scripts', 'admin_scripts');


/**
*	-----------------CUSTOM POST TYPES----------------------
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

// add cmb custom metaboxes
// https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
function be_sample_metaboxes( $meta_boxes ) {
	$prefix = '_cmb_'; // Prefix for all fields

/*
	// course: date + time
	$meta_boxes[] = array(
		'id' => 'date_metabox',
		'title' => 'Date & Time',
		'pages' => array('course'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Date (initial) & time',
				'id' => $prefix . 'date_initial',
				'type' => 'text_datetime_timestamp'
			),
			array(
				'name' => 'Duration',
				'desc' => 'hours',
				'id' => $prefix . 'duration',
				'type' => 'pick_duration'
			),
			array(
				'name' => 'Number of weeks',
				'desc' => '',
				'id' => $prefix . 'no_weeks',
				'type' => 'pick_no_weeks'
			),
			array(
				'name' => 'events',
				'desc' => '',
				'id' => $prefix . 'events_json',
				'type' => 'text_medium'
			),
		),
	);
*/

	// course: location
	$meta_boxes[] = array(
		'id' => 'location_metabox',
		'title' => 'Location',
		'pages' => array('course'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Location',
				'id' => $prefix . 'location',
				'type' => 'pick_location',
			),
		),
	);

	// course: tutor
	$meta_boxes[] = array(
		'id' => 'tutor_metabox',
		'title' => 'Tutor',
		'pages' => array('course'), // post type
		'context' => 'normal',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Name',
				'id' => $prefix . 'tutor',
				'type' => 'text_medium'
			),
		),
	);

	// course: payment details
	$meta_boxes[] = array(
		'id' => 'payment_metabox',
		'title' => 'Payment',
		'pages' => array('course'), // post type
		'context' => 'normal',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Payment details',
				'desc' => 'Theme will append "(please email us about concessions)" unless text contains "free"',
				'id' => $prefix . 'payment',
				'type' => 'text'
			),
		),
	);

	// location: short address
	$meta_boxes[] = array(
		'id' => 'course_short_location_metabox',
		'title' => 'Short address',
		'pages' => array('location'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Short address',
				'desc' => 'e.g. "Chats Palace, Hackney"',
				'id' => $prefix . 'short_address',
				'type' => 'text_medium'
			),
		)
	);

	// location: full address
	$meta_boxes[] = array(
		'id' => 'course_location_metabox',
		'title' => 'Full address',
		'pages' => array('location'), // post type
		'context' => 'normal',
		'priority' => 'default',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Address 1',
				'desc' => '(First line of the address, not the name of the venue)',
				'id' => $prefix . 'address_1',
				'type' => 'text_medium'
			),
			array(
				'name' => 'Address 2',
				'id' => $prefix . 'address_2',
				'type' => 'text_medium'
			),
			array(
				'name' => 'Address 3',
				'id' => $prefix . 'address_3',
				'type' => 'text_medium'
			),
			array(
				'name' => 'City',
				'id' => $prefix . 'city',
				'type' => 'text_medium'
			),
			array(
				'name' => 'Post Code',
				'id' => $prefix . 'post_code',
				'type' => 'text_medium'
			),
			array(
				'name' => 'Map',
				'id' => $prefix . 'google_map_data',
				'type' => 'google_map'
			),
		),
	);

	// location: url
	$meta_boxes[] = array(
		'id' => 'course_location_url_metabox',
		'title' => 'Venue website',
		'pages' => array('location'), // post type
		'context' => 'normal',
		'priority' => 'low',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'URL',
				'id' => $prefix . 'url',
				'type' => 'text_medium'
			),
		)
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'be_sample_metaboxes' );

// Initialize the metabox class
add_action( 'init', 'be_initialize_cmb_meta_boxes', 9999 );
function be_initialize_cmb_meta_boxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( get_template_directory() . '/lib/metaboxes/init.php' );
	}
}


/**
* Define custom meta box field types:
* - pick number of weeks
* - course duration picker
* - location picker
* - google maps
*/
add_action( 'cmb_render_pick_no_weeks', 'rrh_cmb_render_pick_no_weeks', 10, 2 );
function rrh_cmb_render_pick_no_weeks( $field, $meta ) {

	$values = array(1,2,3,4,5,6,7,8,9,10);

	echo '<select class="cmb_select_small" name="', $field['id'], '" id="', $field['id'], '">';
	foreach ( $values as $value ) {
		$selected = $value == $meta ? " selected" : "";
		echo '<option value="' . $value . '"' . $selected .'>' . $value . '</option>';
	}
	echo '</select>';
	echo '<span class="cmb_metabox_description">', $field['desc'], '</span>';
}

add_action( 'cmb_render_pick_duration', 'rrh_cmb_render_pick_duration', 10, 2 );
function rrh_cmb_render_pick_duration( $field, $meta ) {

	$durations = array();
	// populate array with multiples of 1800 = half an hour of unix time
	for ($i=1; $i <= 8; $i++) {
		$durations[] = array(
			"value" => $i * 1800,
			// "label" => $i . ":" . $i % 2 === 0 ? "00" : "30"
			"label" => floor($i/2) . ":" . ((intval($i) % 2 === 0) ? "00" : "30")
		);
	}
	// error_log(print_r($meta));
	$meta = $meta ? $meta : 3600;

	echo '<select class="cmb_select_small" name="', $field['id'], '" id="', $field['id'], '">';
	foreach ( $durations as $duration ) {
		$selected = $duration['value'] == $meta ? " selected" : "";
		echo '<option value="' . $duration['value'] . '"' . $selected .'>' . $duration['label'] . '</option>';
	}
	echo '</select>';
	echo '<span class="cmb_metabox_description">', $field['desc'], '</span>';
}

add_action( 'cmb_render_pick_location', 'rrh_cmb_render_pick_location', 10, 2 );
function rrh_cmb_render_pick_location( $field, $meta ) {
	global $wpdb;

	$locations = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_type = 'location' AND post_status = 'publish'" );

	echo '<select name="', $field['id'], '" id="', $field['id'], '">';
	foreach ( $locations as $location ) {
		$selected = $location->ID == $meta ? " selected" : "";
		echo '<option value="' . $location->ID . '"' . $selected . '>' . $location->post_title . '</option>';
	}
	echo '</select>';
	echo '<span class="cmb_metabox_description">', $field['desc'], '</span>';
}

add_action( 'cmb_render_google_map', 'rrh_cmb_render_google_map', 10, 2 );
function rrh_cmb_render_google_map( $field, $meta ) {
	echo "<input type='hidden' value='" . $meta . "' name='" . $field['id'] . "' id='" . $field['id'] . "'>";
	echo "<div id='_cmb_google_map'></div>";
}





/**
*	-----------------MY METABOXES----------------------
*/

add_action( 'add_meta_boxes', 'ta_add_date_box' );

// backwards compatible (before WP 3.0)
// add_action( 'admin_init', 'myplugin_add_custom_box', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'ta_save_date_data' );

/* Adds a box to the main column on the Post and Page edit screens */
function ta_add_date_box() {
		$screens = array( 'course' );
		foreach ($screens as $screen) {
				add_meta_box(
						'ta_date_picker',
						__( 'Time and Date', 'ta_date_picker' ),
						'ta_print_date_box',
						$screen
				);
		}
}

/* Prints the box content */
function ta_print_date_box( $post ) {

	// Use get_post_meta to retrieve an existing value from the database and use the value for the form
	$meta = get_post_meta( $post->ID );
	$datestamp = intval($meta['_ta_initial_date'][0]);
	if ($datestamp === 0) {
		$datestamp = "";
		$time = date("G:i", 18 * 60 * 60);
	} else {
		$time = date("G:i", $datestamp);
	}
	$duration = intval($meta['_ta_duration'][0]);
	$duration = $duration === 0 ? 3600 : $duration;

	$times = array();
	for ($i=9; $i < 22; $i++) {
		$times[] = $i . ":00";
		$times[] = $i . ":30";
	}

	// store multiples of 1800 = 30 mins unix time
	$durations = array();
	for ($i=1; $i <= 8; $i++) {
		$hours = floor($i/2);
		$minutes = ((intval($i) % 2 === 0) ? "00" : "30");
		$durations[] = array(
			"value" => 1800 * $i,
			"label" => $hours . ":" . $minutes
		);
	}


	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'ta_date_picker_noncename' ); ?>

	<table class="form-table cmb_metabox">
		<tr>
			<th style="width:18%"><label for="">
				<label for="ta_initial_date_field"><?php _e("Date (initial)", 'ta_date_picker' ); ?></label>
			</th>
			<td>
				<input type="text" id="ta_initial_date_field_human" name="ta_initial_date_field_human" value="<?php echo $datestamp !== "" ? date("d/m/Y", $datestamp) : ""; ?>" size="25" />
				<input type="text" id="ta_initial_date_field" name="ta_initial_date_field" value="<?php echo $datestamp !== "" ? date("Y-m-d", $datestamp) : ""; ?>" />
			</td>
		</tr>
		<tr>
			<th style="width:18%"><label for="">
				<label for="ta_time_field"><?php _e("Start time", 'ta_time_picker' ); ?></label>
			</th>
			<td>
				<select class="cmb_select_small" name="ta_time_field" id="ta_time_field">
				<?php foreach ( $times as $t ) {
					$selected = $t === $time ? " selected" : "";
					echo '<option value="' . $t . '"' . $selected . '>' . $t . '</option>';
				} ?>
				</select>
				<span class="cmb_metabox_description"></span>
			</td>
		</tr>
		<tr>
			<th style="width:18%"><label for="">
				<label for="ta_duration_field"><?php _e("Duration", 'ta_duration_picker' ); ?></label>
			</th>
			<td>
				<select class="cmb_select_small" name="ta_duration_field" id="ta_duration_field">
				<?php foreach ( $durations as $d ) {
					$selected = $d['value'] === $duration ? " selected" : "";
					echo '<option value="' . $d['value'] . '"' . $selected . '>' . $d['label'] . '</option>';
				} ?>
				</select>
				<span class="cmb_metabox_description">Hours</span>
			</td>
		</tr>
		<tr>
			<th style="width:18%"><label for="">
				<label for="ta_weeks_field"><?php _e("Number of weeks", 'ta_num_weeks' ); ?></label>
			</th>
			<td>
				<select class="cmb_select_small" name="ta_weeks_field" id="ta_weeks_field">
				<?php for ($i=1; $i < 11; $i++) {
					$selected = $i == intval($meta['_ta_num_weeks'][0]) ? " selected" : "";
					echo '<option value="' . $i . '"' . $selected .'>' . $i . '</option>';
				} ?>
				</select>
				<span class="cmb_metabox_description"></span>
			</td>
		</tr>
	</table>




	<?php

}

/* When the post is saved, saves our custom data */
function ta_save_date_data( $post_id ) {

	// First we need to check if the current user is authorised to do this action.
	if ( 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
				return;
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) )
				return;
	}

	// Secondly we need to check if the user intended to change this value.
	if ( ! isset( $_POST['ta_date_picker_noncename'] ) || ! wp_verify_nonce( $_POST['ta_date_picker_noncename'], plugin_basename( __FILE__ ) ) )
			return;

	// Thirdly we can save the values to the database

	//if saving in a custom table, get post_ID
	$post_ID = $_POST['post_ID'];

	//sanitize user input
	$datestamp = strtotime(sanitize_text_field( $_POST['ta_initial_date_field'] ) . " " . $_POST['ta_time_field']);
	$num_weeks = intval($_POST['ta_weeks_field']);
	$duration = $_POST['ta_duration_field'];


	// save initial date field
	add_post_meta($post_ID, '_ta_initial_date', $datestamp, true) or
		update_post_meta($post_ID, '_ta_initial_date', $datestamp);

	// save duration
	add_post_meta($post_ID, '_ta_duration', $duration, true) or
		update_post_meta($post_ID, '_ta_duration', $duration);

	// save number of weeks
	add_post_meta($post_ID, '_ta_num_weeks', $num_weeks, true) or
		update_post_meta($post_ID, '_ta_num_weeks', $num_weeks);


	// // save individual events
	// delete_post_meta( $post_id, '_ta_course_event' );
	// for ($i=0; $i < $num_weeks; $i++) {
	// 	add_post_meta($post_ID, '_ta_course_event', strval($date->getTimestamp()));
	// 	$date->add($week);
	// }

}





/**
*	-----------------SIDEBAR AND WIDGETS----------------------
*/

// Register our sidebars and widgetized areas.
// copy-pasted from http://codex.wordpress.org/Widgetizing_Themes
function arphabet_widgets_init() {

	register_sidebar( array(
		'name' => 'Home right sidebar',
		'id' => 'home_right_1',
		'before_widget' => '<section class="widget %2$s clearfix">',
		'after_widget' => '</section>',
		'before_title' => '<h3 class="rounded">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'arphabet_widgets_init' );


/**
 * Adds TA_Calendar widget.
 */
class TA_Calendar extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'ta_calendar', // Base ID
			'Trad Academy Upcoming Courses', // Name
			array( 'description' => __( 'Displays all courses scheduled in next 2 weeks', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		$courses = get_courses_for_sidebar();
		$courses_filtered = array();
		$now = time();
		$then = strtotime('+2 weeks');

		foreach ($courses as $course) {
			if ($course['timestamp'] > $now && $course['timestamp'] < $then) {
				$courses_filtered[] = $course;
			}
		}

		if (count($courses_filtered) !== 0) {

			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

				foreach ($courses_filtered as $course) {

					$time = strtotime($course['date'] . " " . $course['start']);
					?>
					<section class="course-summary">
						<h4><a href="<?php echo $course['url']; ?>"><?php echo $course['course']->post_title; ?></a></h4>
						<div>
							<?php echo date( 'D, d M, g:ia', $course['timestamp']); ?>
							<?php if ($course['short address']) { ?>
								<br /><?php echo $course['short address']; ?>
							<?php } ?>
						</div>
					</section>

				<?php }
				echo $after_widget;
		}
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Upcoming courses', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

} // class Foo_Widget
add_action( 'widgets_init', create_function( '', 'register_widget( "TA_Calendar" );' ) );


/*
 * Adds TA_Social_Media widget.
 */
class TA_Social_Media extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'ta_social_media', // Base ID
			'Trad Academy Social Media links', // Name
			array( 'description' => __( 'Facebook something blah', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		// $fb = json_decode(file_get_contents('http://graph.facebook.com/197522990346703'));

		// $url = $fb->link;
		// $name = $fb->name;

		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		// dump($fb);
		?>
		<div class="fb-container">
			<a class="fb-link" href="https://www.facebook.com/tradacademy">The Trad Academy</a>
			<div class="fb-like" data-href="https://facebook.com/tradacademy" data-width="200" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
		</div>
		<?php echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Stay in touch', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

} // class Foo_Widget
add_action( 'widgets_init', create_function( '', 'register_widget( "TA_Social_Media" );' ) );



/**
*	-----------------FUNCTIONS FOR TEMPLATES AND STUFF----------------------
*/

function print_menu () {
	global $wpdb;

	$pages = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish' ORDER BY menu_order" );

	$pages_associative = array();
	foreach ($pages as $page) {
		$pages_associative[$page->ID] = $page;
	}

	$courses = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_type = 'course' AND post_status = 'publish' ORDER BY menu_order" );
	$courses = array_filter($courses, "get_current_courses");

	$tutors = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_type = 'tutor' AND post_status = 'publish' ORDER BY menu_order" );

	$page_hierarchy = get_page_hierarchy( $pages );
	$page_list = array();
	foreach ($page_hierarchy as $ID => $title) {
		// dump();
		$page_list[] = array('ID' => $ID, 'title' => $pages_associative[$ID]->post_title);
	}

	$parents = array(0);

	echo "<ul class='menu'>";
	while (count($page_list) > 0) {
		$page = array_shift($page_list);
		$next_page_parent = $pages_associative[$page_list[0]['ID']]->post_parent;
		echo "<li class='page_item page-item-" . $page['ID'] . "'>";
		echo "<a href='" . get_permalink( $pages_associative[$page['ID']] ) . "'>" . $pages_associative[$page['ID']]->post_title . "</a>";

		if ($page['ID'] == 7){
			echo "<ul>";
			$parents[] = $page['ID'];
			foreach ($courses as $course) {
				echo "<li class='course_item course-item-" . $course->ID . "'>";
				echo "<a href='" . get_permalink( $course->ID ) . "'>" . $course->post_title . "</a>";
				echo "</li>";
			}
			if ($next_page_parent != end($parents)) {
				array_pop($parents);
				echo "</ul>";
			}
		}

		if ($page['ID'] == 11){
			echo "<ul>";
			$parents[] = $page['ID'];
			foreach ($tutors as $tutor) {
				echo "<li class='tutor_item tutor-item-" . $tutor->ID . "'>";
				echo "<a href='" . get_permalink( $tutor->ID ) . "'>" . $tutor->post_title . "</a>";
				echo "</li>";
			}
			if ($next_page_parent != end($parents)) {
				array_pop($parents);
				echo "</ul>";
			}
		}

		if (!in_array($next_page_parent, $parents)) {
			echo "<ul>";
			$parents[] = $next_page_parent;
		} else {
			echo "</li>";
			while ($next_page_parent != end($parents)) {
				array_pop($parents);
				echo "</ul>";
				echo "</li>";
			}
		}
	}
	echo "</ul>";
	?>

<?php }


function print_course_info ($meta, $brief = false) {

	// TIME
	$initial_timestamp = intval($meta['_ta_initial_date'][0]);
	$duration = intval($meta['_ta_duration'][0]);
	$num_weeks = intval($meta['_ta_num_weeks'][0]);

	$initial_date = new DateTime();
	$initial_date->setTimestamp($initial_timestamp);

	if ($num_weeks > 1) {
		$final_date = new DateTime();
		$final_date->setTimestamp($initial_timestamp);
		$final_date->add(new DateInterval("P" . ($num_weeks - 1) . "W"));
		$final_timestamp = $final_date->getTimestamp();
	}

	if ($num_weeks > 1) {
		echo date('l', $initial_timestamp) . "s ∙ ";
		echo date('g:ia', $initial_timestamp) . " - " . date('g:ia', $initial_timestamp + $duration) . " ∙ ";
		echo date('j F Y', $initial_timestamp) . " to " . date('j F Y', $final_timestamp) .  "<br />";
	} else {
		echo date('g:ia', $initial_timestamp) . " - " . date('g:ia', $initial_timestamp + $duration) . ", " ;
		echo date('l d M Y', $initial_timestamp) .  "<br />";
	}

	// PAYMENT
	if (!$brief) {
		$payment = isset($meta['_cmb_payment'][0]) ? $meta['_cmb_payment'][0] : "";
		if ($payment !== "") {
			echo $payment;
			if (strpos(strtolower($payment), "free") === FALSE) {
				echo  " (<a href='mailto:" . get_bloginfo( 'admin_email' ) . "'>email</a> us for information about concessions)<br />";
			} else {
				echo "<br />";
			}
		}
	}

	// LOCATION
	$location = get_post($meta['_cmb_location'][0]);
	$location_meta = get_post_meta($meta['_cmb_location'][0]);
	if (isset($location_meta['_cmb_short_address']) && $location_meta['_cmb_short_address'][0] !== "") {
		$address = $location_meta['_cmb_short_address'][0];
	} elseif (isset($location->post_title)) {
		$address = $location->post_title;
	}
	if ($address != "") {
		echo $address .  "<br />";
	}

	// TUTOR
	$tutor = isset($meta['_cmb_tutor'][0]) ? $meta['_cmb_tutor'][0] : "";
	if ($tutor !== "") {
		echo "With " . $tutor . "<br />";
	}

	// MAIL US
	if (!$brief) {
		echo "<strong>Please <a href='mailto:" . get_bloginfo( 'admin_email' ) . "'>email</a> us to reserve your place</strong><br/>";
	}
}

function print_course_location_map ($meta) {

	$location = get_post($meta['_cmb_location'][0]);
	$location_meta = get_post_meta($meta['_cmb_location'][0]);
	$address_labels = array('_cmb_address_1', '_cmb_address_2', '_cmb_address_3', '_cmb_city', '_cmb_post_code');
	$url = isset($location_meta['_cmb_url']) ? $location_meta['_cmb_url'][0] : "";
	if (isset($location->post_title) && $location->post_title != "") {
		if ($url != "") {
			if (substr($url, 0, 4) != "http://") {
				$url = "http://" . $url;
			}
			$address = "<a href='" . $url . "'>" . $location->post_title . "</a>";
		} else {
			$address = $location->post_title;
		}
	} else {
		$address = "";
	}

	foreach ($address_labels as $label) {
		if (isset($location_meta[$label]) && $location_meta[$label][0] != "") {
			if ($address == "") {
				$address .= $location_meta[$label][0];
			} else {
				$address .= "<br /> " . $location_meta[$label][0];
			}
		}
	}

	if (isset($location_meta['_cmb_google_map_data']) && $location_meta['_cmb_google_map_data'][0] != "") {
		$google_maps_data = $location_meta['_cmb_google_map_data'][0];
	} else {
		$google_maps_data = "false";
	}
	?>

	<div class="address">
		<p><?php echo $address; ?></p>
	</div>
	<div class="google-map" data-googlemaps='<?php echo $google_maps_data; ?>'></div>
	<a class="reset-map" href="#reset-map">Reset map</a>

<?php }

function get_courses_for_sidebar () {
	global $wpdb;

	$courses = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_type = 'course' AND post_status = 'publish'");

	$courses_expanded = array();
	foreach ($courses as $course) {
		$meta = get_post_meta( $course->ID );
		$location = get_post( $meta['_cmb_location'][0] );
		$location_meta = get_post_meta( $meta['_cmb_location'][0] );

		if (isset($location_meta['_cmb_short_address']) && $location_meta['_cmb_short_address'][0] !== "") {
			$short_address = $location_meta['_cmb_short_address'][0];
		} elseif (isset($location->post_title) && $location->post_title !== "") {
			$short_address = $location->post_title;
		} else {
			$short_address = false;
		}

		$initial_date = intval($meta['_ta_initial_date'][0]);
		// $start_time = intval($meta['_ta_start_time'][0]);
		$duration = intval($meta['_ta_duration'][0]);
		$num_weeks = intval($meta['_ta_num_weeks'][0]);

		$date = new DateTime();
		$date->setTimestamp($initial_date);

		for ($i=0; $i < $num_weeks; $i++) {
			$timestamp = $date->getTimestamp();

			$courses_expanded[] = array(
				"timestamp" => $timestamp,
				"duration" => $duration,
				"url" => get_permalink( $course->ID ),
				"course" => $course,
				"location" => $location,
				"short address"  => $short_address,
			);

			$date->add(new DateInterval("P1W"));
		}

	}

	usort($courses_expanded, 'event_sorter');

	return $courses_expanded;

}

function print_social_media_buttons() { ?>
	<div class="social-media-buttons">
		<div class="g-plus" data-action="share" data-annotations="none"></div>
		<a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a>
		<div class="fb-like" data-annotation="none" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
	</div>
	<script>
		(function(d,s,id){
			var
				js,
				fjs=d.getElementsByTagName(s)[0],
				p=/^http:/.test(d.location)?'http':'https';

			if(!d.getElementById(id)){
				js=d.createElement(s);
				js.id=id;
				js.src=p+'://platform.twitter.com/widgets.js';
				fjs.parentNode.insertBefore(js,fjs);
			}

		}(document, 'script', 'twitter-wjs'));
	</script>
<?php }

function print_carousel() {
	global $wpdb;

	$args=array(
		'post_type' => 'carousel_page',
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	$query = new WP_Query($args);
	$carousels = $query->posts;

	?>

 	<div class="carousel-full">
 		<?php
 			$first = true;
 			$counter = 0;
 			$count = count($carousels);
			foreach ($carousels as $i => $carousel):
				$image = get_the_post_thumbnail( $carousel->ID, 'carousel-full' );
				$class = $first ? 'first' : '';
				$first = $false;
				$counter += 1;
				?>
				<div class="carousel-element <?php echo $class; ?>">
					<div class="carousel-text">
						<h2><?php echo $carousel->post_title; ?></h2>
						<p><?php echo apply_filters('the_content',$carousel->post_content); ?></p>
						<div class="controls"><a href="#prev" class="prev">&lt;</a> <?php echo $counter; ?>/<?php echo $count; ?> <a href="#next" class="next">&gt;</a></div>
					</div>
					<?php print_r($image); ?>
				</div>
		<?php endforeach; ?>
	</div>

<?php }




/**
*	-----------------UTILITIES----------------------
*/


function dump($v) {
	echo "<pre>" . print_r($v, true) . "</pre>";
}

function timestamp_to_date($s) {
	dump( date('l jS \of F Y h:i:s A', $s) );
}

function get_current_courses($var) {
	$meta = get_post_meta( $var->ID, $key = '', $single = false );
	$initial_date = $meta['_ta_initial_date'][0];
	$num_weeks = $meta['_ta_num_weeks'][0];
	$final_date = strtotime("+" . ($num_weeks - 1) . " weeks", $initial_date);

	if ( $final_date > time() ) {
		return true;
	} else {
		return false;
	}

}

function event_sorter($a, $b)
{
	if ($a['timestamp'] == $b['timestamp']) {
		return 0;
	}
	return ($a['timestamp'] < $b['timestamp']) ? -1 : 1;
}


?>
