<?php
/**
*   -----------------FUNCTIONS FOR TEMPLATES AND STUFF----------------------
*/

// this is the new version
function print_menu () {
    global $wpdb;

    $pages = $wpdb->get_results( "SELECT *
                                  FROM wp_posts
                                  WHERE post_type = 'page'
                                  AND post_status = 'publish'
                                  ORDER BY post_parent, menu_order",
                                  OBJECT_K );

    $courses = $wpdb->get_results( "SELECT *
                                    FROM wp_posts
                                    WHERE post_type = 'course'
                                    AND post_status = 'publish'
                                    ORDER BY menu_order",
                                    OBJECT_K );
    $courses = array_filter($courses, "get_current_courses");

    $tutors = $wpdb->get_results( "SELECT *
                                   FROM wp_posts
                                   WHERE post_type = 'tutor'
                                   AND post_status = 'publish'
                                   ORDER BY menu_order",
                                   OBJECT_K );

    $ph = get_page_hierarchy( $pages );

    // generate $ph_keys so we can check the next item in the list when we're iterating over
    // the output of get_page_hierarchy
    $ph_keys = array_keys( $ph );

    // put all necessary data about the pages into an array
    $menu_list = array();
    foreach ($ph_keys as $k => $ID) {
        $menu_list[] = array(
            'ID' => $ID,
            'title' => $pages[$ID]->post_title,
            'parent' => $pages[$ID]->post_parent,
            'permalink' => get_permalink( $ID ),
            'type' => 'page'
        );

        // if ID = 7 then add the courses into the list
        if ($ID == 7) {
            foreach ($courses as $course_ID => $course) {
                $meta = get_post_meta( $course->ID, $key = '', $single = false );
                $initial_date = $meta['_ta_initial_date'][0];
                $num_weeks = $meta['_ta_num_weeks'][0];
                $final_date = strtotime("+" . ($num_weeks - 1) . " weeks", $initial_date);

                $m1 = date('F', $initial_date);
                $y1 = date('Y', $initial_date);
                $m2 = date('F', $final_date);
                $y2 = date('Y', $final_date);

                $date_text = $m1;
                if ($y1 != $y2) {
                    $date_text = $date_text . " " . $y1;
                }
                if ($m1 != $m2 || $y1 != $y2) {
                    $date_text = $date_text . " - " . $m2;
                }
                $date_text = $date_text . " " . $y2;

                // $date_text = $m1 . " " . $y1 . " - " . $m2 . " " .$y2;

                $menu_list[] = array(
                    'ID' => $course_ID,
                    'title' => $course->post_title,
                    'dates' => $date_text,
                    'parent' => 7,
                    'permalink' => get_permalink( $course_ID ),
                    'type' => 'course'
                );
            }
            // if there are some courses and the next item on the list is a child of the
            // current item, then we need to add a divider
            if (
                $courses
                & $pages[$ph_keys[$k + 1]]->post_parent == $ID
            ) {
                $menu_list[] = array(
                    'ID' => ':D',
                    'type' => 'divider',
                    'parent' => 7,
                );
            }
        }

        // if ID = 11 then add the tutors into the list
        if ($ID == 11) {
            foreach ($tutors as $tutor_ID => $tutor) {
                $menu_list[] = array(
                    'ID' => $tutor_ID,
                    'title' => $tutor->post_title,
                    'parent' => 11,
                    'permalink' => get_permalink( $tutor_ID ),
                    'type' => 'tutor'
                );
            }
            // if there are some tutors and the next item on the list is a child of the
            // current item, then we need to add a divider
            if (
                $tutors
                & $pages[$ph_keys[$k + 1]]->post_parent == $ID
            ) {
                $menu_list[] = array(
                    'ID' => ':D',
                    'type' => 'divider',
                    'parent' => 11,
                );
            }
        }
    }

    // build recursive array to represent menu
    $menu_tree = buildMenuTree($menu_list);

    ?>

    <nav class="navbar navbar-default clearfix">
        <div class="container">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="visually-hidden">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <form class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>


            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="primary_nav_wrap">

            <?php print_menu_recursive($menu_tree); ?>

            </div><!-- /.navbar-collapse -->

        </div>
    </nav>

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


// returns an array of all courses with events in the future with the post_meta stored in the 'meta' property
function get_forthcoming_courses_and_metadata() {
    global $wpdb;
    $output = [];

    $courses = $wpdb->get_results("SELECT * FROM wp_posts
                                   WHERE post_type = 'course'
                                   AND post_status = 'publish'");

    foreach ($courses as $course) {
        $meta = get_post_meta( $course->ID );
        $course->meta = $meta;

        if ( are_events_forthcoming($course) ) {
            $output[] = $course; 
        }
    }

    return $output;
}

// takes a course object with added "meta" property,
// and returns true or false depending on whether that course has any events which start in the future
function are_events_forthcoming($course) {
    $cutoff = new DateTime();

    $initial_date = intval($course->meta['_ta_initial_date'][0]);
    $num_weeks = intval($course->meta['_ta_num_weeks'][0]);

    $final_date = new DateTime();
    $final_date->setTimestamp($initial_date);

    $final_date->add(new DateInterval("P" . ($num_weeks - 1) . "W")); // counts forward by ($num_weeks - 1) weeks

    $output = $final_date > $cutoff;

    return $output;
}

// takes a course object with added "meta" property,
// and returns the date of the first event in the future
// or FALSE if no events in the future.
function get_next_event_date($course) {
    $cutoff = new DateTime();

    $initial_date = intval($course->meta['_ta_initial_date'][0]);
    $num_weeks = intval($course->meta['_ta_num_weeks'][0]);

    $event_date = new DateTime();
    $event_date->setTimestamp($initial_date);

    for ($i=0; $i < $num_weeks; $i++) { 

        if ($event_date > $cutoff) {
            return $event_date;
        }

        $event_date->add(new DateInterval("P1W"));
    }

    return false;
}

// takes the ID of a course location
// and returns a string containing the short address
function get_short_address($id) {
    $location = get_post($id);
    $location_meta = get_post_meta($id);

    if (isset($location_meta['_cmb_short_address']) && $location_meta['_cmb_short_address'][0] !== "") {
        $short_address = $location_meta['_cmb_short_address'][0];
    } elseif (isset($location->post_title) && $location->post_title !== "") {
        $short_address = $location->post_title;
    } else {
        $short_address = false;
    }

    return $short_address;
}


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

function print_whats_coming_up_carousel() { 

    global $wpdb;

    $courses = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_type = 'course' AND post_status = 'publish'");

    $coming_up = array();
    $cutoff = new DateTime();

    foreach ($courses as $course) {
        $meta = get_post_meta( $course->ID );
        $location = get_post( $meta['_cmb_location'][0] );
        $location_meta = get_post_meta( $meta['_cmb_location'][0] );

        if (isset($location_meta['_cmb_short_address']) && $location_meta['_cmb_short_address'][0] !== "") {
            $short_address = $location_meta['_cmb_short_address'][0];
        } elseif (isset($location->post_title) && $location->post_title !== "") {
            $short_address = $location->post_title;
        }

        $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($course->ID), 'medium');

        $initial_date = intval($meta['_ta_initial_date'][0]);
        // $start_time = intval($meta['_ta_start_time'][0]);
        $duration = intval($meta['_ta_duration'][0]);
        $num_weeks = intval($meta['_ta_num_weeks'][0]);

        $date = new DateTime();
        $date->setTimestamp($initial_date);


        for ($i=0; $i < $num_weeks; $i++) {
            $timestamp = $date->getTimestamp();

            if ($date > $cutoff) {
                $coming_up[] = array(
                    "url" => get_permalink( $course->ID ),
                    "title" => $course->post_title,
                    "thumbnail" => $thumbnail,
                    "timestamp" => $timestamp,
                    "date" => date( 'D, d M, Y, g:ia', $timestamp ),
                    "location"  => $short_address,
                    "excerpt" => wp_trim_words( $course->post_content, 40 ),
                );
                break;
            }

            $date->add(new DateInterval("P1W"));
        }

    }
    usort($coming_up, 'event_sorter');

    ?>

    <section id="coming-up">
        <div class="vertical-section">
            <header>
                <h2 class="text-center">What's coming up?</h2>
            </header>
            <div class="carousel">

            <?php foreach ($coming_up as $course) { ?>

                <div>
                    <a href="<?= $course['url']; ?>">
                        <h3><?= $course['title']; ?></h3>
                    </a>
                    <div class="image" style="background-image: url('<?= $course['thumbnail'][0]; ?>')"></div>
                    <!-- <img src="<?= $course['thumbnail'][0]; ?>"> -->
                    <div class="date"><?= $course['date']; ?></div>
                    <div class="location"><?= $course['location']; ?></div>
                    <div><?= $course['excerpt']; ?></div>
                </div>

            <?php } ?>              

            </div>
            <div class="button">View all upcoming courses</div>
        </div>
    </section>

<?php }

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

/*
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
                $first = false;
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
*/