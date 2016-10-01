<?php
/**
*   -----------------DEFINE CUSTOM METABOXES----------------------
*/

// add cmb custom metaboxes
// https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
function be_sample_metaboxes( $meta_boxes ) {
    $prefix = '_cmb_'; // Prefix for all fields

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
 * Load the CMB2 library
 */

if ( file_exists( get_template_directory() . '/lib/cmb2/init.php' ) ) {
    require_once get_template_directory() . '/lib/cmb2/init.php';
} elseif ( file_exists( get_template_directory() . '/lib/CMB2/init.php' ) ) {
    require_once get_template_directory() . '/lib/CMB2/init.php';
}

/**
*   Define a google-style datepicker to use with CMB2 library
*/

function cmb2_render_callback_for_google_style_datepicker( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    // echo $field_type_object->input( array( 'type' => 'email' ) );
    // echo "<h1>Hello</h1>";
    ?>
        <div class="cmb-th">
            <label>Date</label>
        </div>
        <div class="cmb-td date-range-initial">
            <input type="text" class="date human start" />
            <input type="text" class="robot start hidden" />
            <input type="text" class="time start" /> to
            <input type="text" class="time end" />
            <input type="text" class="date human end" />
            <input type="text" class="robot end hidden" />
        </div>

    <?php
}
add_action( 'cmb2_render_google_style_datepicker', 'cmb2_render_callback_for_google_style_datepicker', 10, 5 );






add_action( 'cmb2_admin_init', 'ta_register_repeatable_daterange_field_metabox' );
/**
 * Hook in and add a repeatable daterange group of metaboxes
 */
function ta_register_repeatable_daterange_field_metabox() {
    $prefix = 'ta_daterange_';

    /**
     * Repeatable Field Groups
     */
    $ta_daterange_group = new_cmb2_box( array(
        'id'           => $prefix . 'metabox',
        'title'        => __( 'Date (range(s))', 'cmb2' ),
        'object_types' => array( 'course', ),
    ) );

    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $group_field_id = $ta_daterange_group->add_field( array(
        'id'          => $prefix . 'group',
        'type'        => 'group',
        'description' => __( "Date ranges. You can pick more than one, in case there's a break or something", 'cmb2' ),
        'options'     => array(
            'group_title'   => __( 'Date range {#}', 'cmb2' ), // {#} gets replaced by row number
            'add_button'    => __( 'Add Another Date Range', 'cmb2' ),
            'remove_button' => __( 'Remove Date Range', 'cmb2' ),
            // 'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
        ),
    ) );

    /**
     * Group fields works the same, except ids only need
     * to be unique to the group. Prefix is not needed.
     *
     * The parent field's id needs to be passed as the first argument.
     */

    $ta_daterange_group->add_group_field( $group_field_id, array(
        // 'name' => __( 'All day', 'cmb2' ),
        // 'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'date',
        'type' => 'google_style_datepicker',
    ) );

    $ta_daterange_group->add_group_field( $group_field_id, array(
        'name'        => __( 'All day', 'cmb2' ),
        // 'desc'        => __( 'field description (optional)', 'cmb2' ),
        'id'          => $prefix . 'all-day',
        'type'        => 'checkbox',
        'row_classes' => 'all-day',
    ) );

    $ta_daterange_group->add_group_field( $group_field_id, array(
        'name' => __( 'Repeats', 'cmb2' ),
        // 'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'repeats',
        'type' => 'checkbox',
        'row_classes' => 'repeats',
    ) );

    $ta_daterange_group->add_group_field( $group_field_id, array(
        'name'             => __( 'Number of weeks', 'cmb2' ),
        'desc'             => __( ' ', 'cmb2' ), // leave this blank - calculated final date 
                                                // is inserted here via JS
        'id'               => $prefix . 'weeks',
        'type'             => 'select',
        'row_classes'      => 'weeks',
        'options'          => array(
            '2' => __( '2', 'cmb2' ),
            '3' => __( '3', 'cmb2' ),
            '4' => __( '4', 'cmb2' ),
            '5' => __( '5', 'cmb2' ),
            '6' => __( '6', 'cmb2' ),
            '7' => __( '7', 'cmb2' ),
            '8' => __( '8', 'cmb2' ),
            '9' => __( '9', 'cmb2' ),
            '10' => __( '10', 'cmb2' ),
        ),
    ) );

}





/**
*   Old (PZA1) date picker
*/

add_action( 'add_meta_boxes', 'ta_add_date_box' );

// backwards compatible (before WP 3.0)
// add_action( 'admin_init', 'myplugin_add_custom_box', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'ta_save_date_data' );

/* Adds a box to the main column on the course edit screens */
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

    // we need to check if the user intended to change this value.
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
    //  add_post_meta($post_ID, '_ta_course_event', strval($date->getTimestamp()));
    //  $date->add($week);
    // }

}
