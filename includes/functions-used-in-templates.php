<?php
/**
*   -----------------FUNCTIONS FOR TEMPLATES AND STUFF----------------------
*/

// this is the old version, to be got rid of (i think)
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
        // dump($page_list);
        // dump(count($page_list));
        // dump("poo");

        $page = array_shift($page_list);
        
        if (count($page_list) > 0) {
            $next_page_parent = $pages_associative[$page_list[0]['ID']]->post_parent;
        } else {
            $next_page_parent = "all gone";
        }
        echo "<li class='page_item page-item-" . $page['ID'] . "'>";
        echo "<a href='" . get_permalink( $pages_associative[$page['ID']] ) . "'>" . $pages_associative[$page['ID']]->post_title . "</a>";

        // special behaviour to create children of "what's on" page
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

        // special behaviour to create children of "tutors" page
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


function print_menu_2 () {
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

    // dump($courses);

    $parents = array(0);
    ?>

    <nav class="navbar navbar-default clearfix">
        <div class="container container-fluid">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
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
                <ul>
                    <li class="dropdown">
                        <a href="#" >What's on</a>
                        <ul>
                        <?php foreach ($courses as $course) { ?>
                            <li><a href="<?= get_permalink( $course->ID ); ?>"><?= $course->post_title; ?></a></li>
                        <?php } ?>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Previous courses</a></li>
                        </ul>
                    </li>
                    <li><a href="<?= get_permalink( get_option( 'page_for_posts' ) ); ?>">News</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->

        
        </div><!-- /.container-fluid -->
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
        <div class="container">
            <h2 class="text-center">What's coming up?</h2>
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
