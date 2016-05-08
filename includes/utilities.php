<?php
/**
*   -----------------UTILITIES----------------------
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